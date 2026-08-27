<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, 'memberNo' => $this->member_no, 'name' => $this->full_name,
            'identity' => $this->identity_number, 'occupation' => $this->occupation,
            'birthDate' => $this->birth_date?->format('Y-m-d'), 'address' => $this->address,
            'phone' => $this->phone, 'email' => $this->email,
            'emergencyName' => $this->emergency_contact_name, 'emergencyPhone' => $this->emergency_phone,
            'membershipType' => $this->membership_type, 'durationMonths' => $this->duration_months,
            'validFrom' => $this->valid_from?->format('Y-m-d'), 'validThrough' => $this->valid_through?->format('Y-m-d'),
            'paymentType' => $this->payment_type, 'contractAmount' => $this->contract_amount,
            'invoiceAddress' => $this->invoice_address, 'status' => $this->status,
        ];
    }
}
