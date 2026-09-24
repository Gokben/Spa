<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Models\MemberPayment;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class MemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = Member::query()->orderBy('full_name')->get()->map(fn (Member $member) => [
            'id' => $member->id, 'memberNo' => $member->member_no, 'name' => $member->full_name,
            'phone' => $member->phone, 'membershipType' => $member->membership_type,
            'validThrough' => $member->valid_through?->format('Y-m-d'), 'status' => $member->status,
        ]);

        return response()->json(['data' => $members]);
    }

    public function show(Member $member): MemberResource
    {
        return new MemberResource($member);
    }

    public function services(Member $member): JsonResponse
    {
        $reservations = Reservation::query()
            ->whereBelongsTo($member)
            ->with([
                'employee:id,first_name,last_name',
                'items' => fn ($query) => $query->where('type', 'package')->orderBy('id'),
            ])
            ->orderByDesc('reservation_date')
            ->orderByDesc('start_time')
            ->get();

        $services = $reservations->flatMap(function (Reservation $reservation) {
            $items = $reservation->items;
            if ($items->isEmpty()) {
                $items = collect([(object) [
                    'id' => null,
                    'name' => $reservation->service_name,
                    'unit_price' => null,
                    'currency' => 'EUR',
                ]]);
            }

            return $items->map(fn ($item) => [
                'id' => $item->id,
                'reservation_id' => $reservation->id,
                'reservation_date' => $reservation->reservation_date?->format('Y-m-d'),
                'start_time' => substr((string) $reservation->start_time, 0, 5),
                'end_time' => substr((string) $reservation->end_time, 0, 5),
                'service_name' => $item->name,
                'employee_name' => $reservation->employee
                    ? trim($reservation->employee->first_name.' '.$reservation->employee->last_name)
                    : null,
                'status' => $reservation->status,
                'unit_price' => $item->unit_price,
                'currency' => $item->currency,
            ]);
        })->values();

        return response()->json(['data' => [
            'member' => ['id' => $member->id, 'memberNo' => $member->member_no, 'name' => $member->full_name],
            'services' => $services,
        ]]);
    }

    public function store(MemberRequest $request): MemberResource
    {
        return new MemberResource(Member::create($this->attributes($request->validated())));
    }

    public function update(MemberRequest $request, Member $member): MemberResource
    {
        $member->update($this->attributes($request->validated()));

        return new MemberResource($member->refresh());
    }

    public function destroy(Member $member): JsonResponse
    {
        if (MemberPayment::query()->whereBelongsTo($member)->exists()) {
            throw ValidationException::withMessages([
                'member' => 'Tahsilat kaydı bulunan misafir silinemez. Önce Hizmetler ekranındaki tahsilatları kaldırın.',
            ]);
        }

        $photoPath = $member->photo_path;
        $member->delete();

        if ($photoPath && str_starts_with($photoPath, 'members/')) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($photoPath);
        }

        return response()->json(['message' => 'Misafir kaydı silindi.']);
    }

    public function uploadPhoto(\Illuminate\Http\Request $request, Member $member): MemberResource
    {
        $data = $request->validate(['photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']], [
            'photo.image' => 'Geçerli bir fotoğraf seçin.',
            'photo.mimes' => 'Fotoğraf JPG, PNG veya WebP olmalıdır.',
            'photo.max' => 'Fotoğraf en fazla 5 MB olabilir.',
        ]);
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $path = $data['photo']->store('members', 'local');
        abort_unless($path, 500, 'Fotoğraf kaydedilemedi.');
        $previous = $member->photo_path;
        try {
            $member->photo_path = $path;
            $member->save();
        } catch (\Throwable $error) {
            $disk->delete($path);
            throw $error;
        }
        if ($previous && str_starts_with($previous, 'members/')) $disk->delete($previous);
        return new MemberResource($member->refresh());
    }

    public function photo(Member $member)
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        abort_unless($member->photo_path && $disk->exists($member->photo_path), 404);
        return response()->file($disk->path($member->photo_path), ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
    private function attributes(array $data): array
    {
        return [
            'member_no' => $data['memberNo'],
            'first_name' => trim($data['firstName']), 'last_name' => trim($data['lastName']),
            'full_name' => trim($data['firstName'].' '.$data['lastName']),
            'identity_number' => $data['identity'] ?? null, 'occupation' => $data['occupation'] ?? null,
            'birth_date' => $data['birthDate'] ?? null, 'blood_group' => $data['bloodGroup'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null, 'email' => $data['email'] ?? null,
            'emergency_contact_name' => $data['emergencyName'] ?? null, 'emergency_phone' => $data['emergencyPhone'] ?? null,
            'membership_type' => $data['membershipType'], 'duration_months' => $data['durationMonths'] ?? null,
            'valid_from' => $data['validFrom'] ?? null, 'valid_through' => $data['validThrough'] ?? null,
            'payment_type' => $data['paymentType'] ?? null, 'contract_amount' => $data['contractAmount'] ?? null,
            'invoice_address' => $data['invoiceAddress'] ?? null, 'status' => $data['status'],
        ];
    }
}
