<?php

namespace App\Http\Controllers;

use App\Models\CashCategory;
use App\Models\CashClosing;
use App\Models\CashTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CashController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $transactions = CashTransaction::query()->with('category')->orderByDesc('transaction_date')->orderByDesc('id');
        if ($request->filled('date_from')) $transactions->whereDate('transaction_date', '>=', $request->date('date_from'));
        if ($request->filled('date_to')) $transactions->whereDate('transaction_date', '<=', $request->date('date_to'));

        $opening = (float) DB::table('cash_settings')->where('id', 1)->value('opening_balance');
        $income = (float) CashTransaction::where('type', 'income')->sum('amount');
        $expense = (float) CashTransaction::where('type', 'expense')->sum('amount');

        return response()->json(['data' => [
            'opening_balance' => $opening,
            'income_total' => $income,
            'expense_total' => $expense,
            'balance' => $opening + $income - $expense,
            'transactions' => $transactions->get(),
            'categories' => CashCategory::query()->orderBy('type')->orderBy('name')->get(),
            'closings' => CashClosing::query()->orderByDesc('closing_date')->get(),
        ]]);
    }

    public function saveOpening(Request $request): JsonResponse
    {
        $data = $request->validate(['opening_balance' => ['required', 'numeric']]);
        DB::table('cash_settings')->updateOrInsert(['id' => 1], ['opening_balance' => $data['opening_balance'], 'updated_at' => now()]);

        return response()->json(['data' => ['opening_balance' => (float) $data['opening_balance']]]);
    }

    public function storeTransaction(Request $request): JsonResponse
    {
        return response()->json(['data' => CashTransaction::create($this->transactionData($request))->load('category')], 201);
    }

    public function updateTransaction(Request $request, CashTransaction $cashTransaction): JsonResponse
    {
        $cashTransaction->update($this->transactionData($request));

        return response()->json(['data' => $cashTransaction->refresh()->load('category')]);
    }

    public function destroyTransaction(CashTransaction $cashTransaction): JsonResponse
    {
        $cashTransaction->delete();

        return response()->json([], 204);
    }

    public function storeCategory(Request $request): JsonResponse
    {
        $data = $this->categoryData($request);

        return response()->json(['data' => CashCategory::create($data)], 201);
    }

    public function updateCategory(Request $request, CashCategory $cashCategory): JsonResponse
    {
        $cashCategory->update($this->categoryData($request, $cashCategory));

        return response()->json(['data' => $cashCategory->refresh()]);
    }

    public function destroyCategory(CashCategory $cashCategory): JsonResponse
    {
        if ($cashCategory->transactions()->exists()) {
            throw ValidationException::withMessages(['category' => 'Kullanılmış kategori silinemez; pasif duruma getirebilirsiniz.']);
        }
        $cashCategory->delete();

        return response()->json([], 204);
    }

    public function saveClosing(Request $request): JsonResponse
    {
        $data = $request->validate([
            'closing_date' => ['required', 'date'],
            'counted_balance' => ['required', 'numeric'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $opening = (float) DB::table('cash_settings')->where('id', 1)->value('opening_balance');
        $income = (float) CashTransaction::whereDate('transaction_date', '<=', $data['closing_date'])->where('type', 'income')->sum('amount');
        $expense = (float) CashTransaction::whereDate('transaction_date', '<=', $data['closing_date'])->where('type', 'expense')->sum('amount');
        $expected = $opening + $income - $expense;
        $closing = CashClosing::updateOrCreate(
            ['closing_date' => $data['closing_date']],
            ['expected_balance' => $expected, 'counted_balance' => $data['counted_balance'], 'difference' => (float) $data['counted_balance'] - $expected, 'note' => $data['note'] ?? null],
        );

        return response()->json(['data' => $closing]);
    }

    private function transactionData(Request $request): array
    {
        $data = $request->validate([
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_type' => ['required', Rule::in(['cash', 'credit_card', 'transfer', 'room_charge'])],
            'category_id' => ['nullable', 'integer', 'exists:cash_categories,id'],
            'document_no' => ['nullable', 'string', 'max:100'],
        ]);
        if (!empty($data['category_id'])) {
            $category = CashCategory::find($data['category_id']);
            if (!$category?->active || $category->type !== $data['type']) {
                throw ValidationException::withMessages(['category_id' => 'İşlem türüne uygun aktif bir kategori seçiniz.']);
            }
        }

        return $data;
    }

    private function categoryData(Request $request, ?CashCategory $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('cash_categories')->where(fn ($query) => $query->where('type', $request->input('type')))->ignore($category?->id)],
            'type' => ['required', Rule::in(['income', 'expense'])],
            'active' => ['required', 'boolean'],
        ]);
    }
}
