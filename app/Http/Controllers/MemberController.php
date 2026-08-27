<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\JsonResponse;

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

    public function store(MemberRequest $request): MemberResource
    {
        return new MemberResource(Member::create($this->attributes($request->validated())));
    }

    public function update(MemberRequest $request, Member $member): MemberResource
    {
        $member->update($this->attributes($request->validated()));

        return new MemberResource($member->refresh());
    }

    private function attributes(array $data): array
    {
        return [
            'member_no' => $data['memberNo'], 'full_name' => $data['name'],
            'identity_number' => $data['identity'] ?? null, 'occupation' => $data['occupation'] ?? null,
            'birth_date' => $data['birthDate'] ?? null, 'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null, 'email' => $data['email'] ?? null,
            'emergency_contact_name' => $data['emergencyName'] ?? null, 'emergency_phone' => $data['emergencyPhone'] ?? null,
            'membership_type' => $data['membershipType'], 'duration_months' => $data['durationMonths'] ?? null,
            'valid_from' => $data['validFrom'] ?? null, 'valid_through' => $data['validThrough'] ?? null,
            'payment_type' => $data['paymentType'] ?? null, 'contract_amount' => $data['contractAmount'] ?? null,
            'invoice_address' => $data['invoiceAddress'] ?? null, 'status' => $data['status'],
        ];
    }
}
