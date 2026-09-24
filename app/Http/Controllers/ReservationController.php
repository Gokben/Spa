<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Reservation;
use App\Models\SpaPackage;
use App\Models\StockItem;
use App\ReservationChangeSmsNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function show(Reservation $reservation): JsonResponse
    {
        return response()->json(['data' => $reservation->load(['member', 'employee', 'items'])]);
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'start' => ['nullable', 'required_with:end', 'date_format:Y-m-d'],
            'end' => ['nullable', 'required_with:start', 'date_format:Y-m-d', 'after:start'],
        ]);
        $month = $filters['month'] ?? now()->format('Y-m');
        $start = $filters['start'] ?? $month.'-01';
        $end = $filters['end'] ?? date('Y-m-d', strtotime($start.' +1 month'));

        return response()->json(['data' => [
            'web_requests' => Reservation::query()->where('notes', 'like', '[WEB]%')->whereNull('employee_id')->where('status', 'planned')->orderBy('created_at')->limit(100)->get(),
            'month' => substr($start, 0, 7),
            'start' => $start,
            'end' => $end,
            'reservations' => Reservation::query()->with(['member', 'employee', 'items'])->whereDate('reservation_date', '>=', $start)->whereDate('reservation_date', '<', $end)->orderBy('reservation_date')->orderBy('start_time')->get(),
            'members' => Member::query()->where('status', 'aktif')->orderBy('full_name')->get(['id', 'member_no', 'full_name', 'phone'])->map(fn (Member $member) => [
                'id' => $member->id,
                'member_no' => $member->member_no,
                'name' => $member->full_name,
                'phone' => $member->phone,
            ]),
            'employees' => Employee::query()->with('occupation')->where('status', 'aktif')->orderBy('first_name')->orderBy('last_name')->get(['id', 'first_name', 'last_name', 'occupation_id']),
            'packages' => SpaPackage::query()
                ->with('serviceGroup:id,name')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id', 'name', 'duration_text', 'price', 'service_group_id']),
        ]]);
    }

    public function store(Request $request): JsonResponse
    {
        [$data, $items, $hasItems] = $this->validated($request);
        $this->ensureAvailable($data);

        $reservation = DB::transaction(function () use ($data, $items, $hasItems) {
            $reservation = Reservation::create($data);
            if ($hasItems) {
                $this->syncItems($reservation, $items);
            }

            return $reservation;
        });

        return response()->json(['data' => $reservation->load(['member', 'employee', 'items'])], 201);
    }

    public function update(Request $request, Reservation $reservation, ReservationChangeSmsNotifier $notifier): JsonResponse
    {
        $original = $reservation->only(['employee_id', 'start_time', 'end_time']);
        [$data, $items, $hasItems] = $this->validated($request);
        $this->ensureAvailable($data, $reservation);
        DB::transaction(function () use ($reservation, $data, $items, $hasItems) {
            $reservation->update($data);
            if ($hasItems) {
                $this->syncItems($reservation, $items);
            }
        });
        $reservation = $reservation->refresh()->load(['member', 'employee', 'items']);

        return response()->json([
            'data' => $reservation,
            'sms_notification' => $notifier->notify($reservation, $original),
        ]);
    }

    public function updateStatus(Request $request, Reservation $reservation): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['planned', 'confirmed', 'completed', 'cancelled', 'no_show'])],
        ]);
        $reservation->update($data);

        return response()->json(['data' => $reservation->refresh()]);
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        if (MemberPayment::where('reservation_id', $reservation->id)->exists()) {
            throw ValidationException::withMessages(['reservation' => 'Tahsilatı bulunan rezervasyon silinemez. Önce misafirin Hizmetler ekranındaki tahsilatı kaldırın.']);
        }
        $reservation->delete();

        return response()->json([], 204);
    }

    private function validated(Request $request): array
    {
        $hasItems = $request->exists('items_json') || $request->exists('items');
        if ($request->exists('items_json')) {
            $request->merge(['items' => json_decode((string) $request->input('items_json'), true)]);
        }
        $data = $request->validate([
            'member_id' => ['nullable', 'integer', 'exists:members,id'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'guest_name' => ['required', 'string', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'service_name' => ['required', 'string', 'max:190'],
            'reservation_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', Rule::in(['planned', 'confirmed', 'completed', 'cancelled', 'no_show'])],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['nullable', 'array'],
            'items.*.type' => ['required', Rule::in(['package', 'stock'])],
            'items.*.id' => ['required', 'integer'],
        ]);
        $items = $data['items'] ?? [];
        unset($data['items']);
        if (! empty($data['member_id'])) {
            $member = Member::find($data['member_id']);
            $data['guest_name'] = $member->full_name;
            $data['phone'] = $member->phone;
        }

        return [$data, $items, $hasItems];
    }

    private function syncItems(Reservation $reservation, array $items): void
    {
        $rows = [];
        foreach (collect($items)->unique(fn (array $item) => $item['type'].':'.$item['id']) as $item) {
            if ($item['type'] === 'package') {
                $package = SpaPackage::find($item['id']);
                if (! $package) {
                    throw ValidationException::withMessages(['items' => 'Seçilen hizmet artık mevcut değil.']);
                }
                $rows[] = [
                    'spa_package_id' => $package->id,
                    'stock_item_id' => null,
                    'type' => 'package',
                    'name' => $package->name,
                    'unit_price' => $package->price ?? 0,
                    'currency' => 'EUR',
                ];
            } else {
                $stockItem = StockItem::find($item['id']);
                if (! $stockItem) {
                    throw ValidationException::withMessages(['items' => 'Seçilen stok kartı artık mevcut değil.']);
                }
                $rows[] = [
                    'spa_package_id' => null,
                    'stock_item_id' => $stockItem->id,
                    'type' => 'stock',
                    'name' => $stockItem->name,
                    'unit_price' => $stockItem->sale_price,
                    'currency' => 'TRY',
                ];
            }
        }

        $reservation->items()->delete();
        $reservation->items()->createMany($rows);
    }

    private function ensureAvailable(array $data, ?Reservation $reservation = null): void
    {
        if (empty($data['employee_id']) || $data['status'] === 'cancelled') {
            return;
        }
        $conflict = Reservation::query()
            ->where('employee_id', $data['employee_id'])
            ->whereDate('reservation_date', $data['reservation_date'])
            ->where('status', '!=', 'cancelled')
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->when($reservation, fn ($query) => $query->whereKeyNot($reservation->id))
            ->exists();
        if ($conflict) {
            throw ValidationException::withMessages(['start_time' => 'Seçilen personelin bu saat aralığında başka bir rezervasyonu var.']);
        }
    }
}
