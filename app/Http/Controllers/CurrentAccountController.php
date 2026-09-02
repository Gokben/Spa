<?php

namespace App\Http\Controllers;

use App\Models\CurrentAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrentAccountController extends Controller
{
    public function index(): JsonResponse
    {
        $accounts = CurrentAccount::query()
            ->withCount('invoices')
            ->orderBy('code')
            ->get();

        return response()->json(['data' => $accounts]);
    }

    public function show(CurrentAccount $currentAccount): JsonResponse
    {
        $currentAccount->load([
            'invoices' => fn ($query) => $query
                ->with(['items.stockItem'])
                ->orderByDesc('movement_date')
                ->orderByDesc('id'),
        ]);

        return response()->json(['data' => $currentAccount]);
    }

    public function store(Request $request): JsonResponse
    {
        $account = CurrentAccount::create($this->validated($request));

        return response()->json(['data' => $account], 201);
    }

    public function update(Request $request, CurrentAccount $currentAccount): JsonResponse
    {
        $currentAccount->update($this->validated($request, $currentAccount));

        return response()->json(['data' => $currentAccount->refresh()]);
    }

    public function destroy(CurrentAccount $currentAccount): JsonResponse
    {
        $currentAccount->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request, ?CurrentAccount $currentAccount = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('current_accounts')->ignore($currentAccount?->id)],
            'title' => ['required', 'string', 'max:190'],
            'short_name' => ['required', 'string', 'max:120'],
            'tax_office' => ['nullable', 'string', 'max:120'],
            'tax_number' => ['nullable', 'string', 'max:20'],
            'type' => ['required', Rule::in(['tedarikci', 'musteri', 'diger'])],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'authorized_person' => ['nullable', 'string', 'max:150'],
            'invoice_address' => ['nullable', 'string', 'max:2000'],
            'company_detail' => ['nullable', 'string', 'max:2000'],
            'has_internal_service' => ['sometimes', 'boolean'],
            'has_external_service' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::in(['aktif', 'pasif'])],
        ]);
    }
}
