<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\StockItem;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function index(): JsonResponse
    {
        $items = StockItem::query()
            ->with('categories')
            ->withSum(['movements as entry_quantity' => fn ($query) => $query->where('type', 'giris')], 'quantity')
            ->withSum(['movements as exit_quantity' => fn ($query) => $query->where('type', 'cikis')], 'quantity')
            ->orderBy('name')
            ->get()
            ->each(fn (StockItem $item) => $item->setAttribute('quantity', (float) ($item->entry_quantity ?? 0) - (float) ($item->exit_quantity ?? 0)));

        return response()->json(['data' => $items]);
    }

    public function show(StockItem $stockItem): JsonResponse
    {
        return response()->json(['data' => $stockItem->load('categories')]);
    }

    public function store(Request $request): JsonResponse
    {
        $item = DB::transaction(function () use ($request): StockItem {
            [$data, $categoryIds] = $this->itemData($request);
            $item = StockItem::create($data);
            $item->categories()->sync($categoryIds);

            return $item->load('categories');
        });

        return response()->json(['data' => $item], 201);
    }

    public function update(Request $request, StockItem $stockItem): JsonResponse
    {
        DB::transaction(function () use ($request, $stockItem): void {
            [$data, $categoryIds] = $this->itemData($request, $stockItem);
            $stockItem->update($data);
            $stockItem->categories()->sync($categoryIds);
        });

        return response()->json(['data' => $stockItem->refresh()->load('categories')]);
    }

    public function destroy(StockItem $stockItem): JsonResponse
    {
        $stockItem->delete();

        return response()->json([], 204);
    }

    public function movements(Request $request): JsonResponse
    {
        $query = StockMovement::query()->with('stockItem')->orderByDesc('movement_date')->orderByDesc('id');
        if ($request->filled('stock_item_id')) {
            $query->where('stock_item_id', $request->integer('stock_item_id'));
        }

        return response()->json(['data' => $query->get()]);
    }

    public function storeMovement(Request $request): JsonResponse
    {
        $data = $request->validate([
            'stock_item_id' => ['required', 'integer', 'exists:stock_items,id'],
            'type' => ['required', Rule::in(['giris', 'cikis'])],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'movement_date' => ['required', 'date'],
            'document_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $movement = DB::transaction(function () use ($data) {
            $item = StockItem::query()->lockForUpdate()->findOrFail($data['stock_item_id']);
            if ($data['type'] === 'cikis') {
                $balance = (float) $item->movements()->selectRaw("COALESCE(SUM(CASE WHEN type = 'giris' THEN quantity ELSE -quantity END), 0) AS balance")->value('balance');
                if ((float) $data['quantity'] > $balance) {
                    throw ValidationException::withMessages(['quantity' => "Mevcut stok {$balance} {$item->unit}; bu miktardan fazla çıkış yapılamaz."]);
                }
            }

            return StockMovement::create($data);
        });

        return response()->json(['data' => $movement->load('stockItem')], 201);
    }

    private function itemData(Request $request, ?StockItem $stockItem = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', Rule::unique('stock_items')->ignore($stockItem?->id)],
            'name' => ['required', 'string', 'max:190'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'distinct', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:120'],
            'unit' => ['required', Rule::in(['Adet', 'Kutu', 'Paket', 'Şişe', 'Tüp', 'Kilogram', 'Litre'])],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['aktif', 'pasif'])],
        ]);

        $categoryIds = array_values($data['category_ids'] ?? []);
        unset($data['category_ids']);
        $data['category'] = $categoryIds
            ? Category::query()->whereIn('id', $categoryIds)->orderBy('name')->value('name')
            : null;

        return [$data, $categoryIds];
    }
}
