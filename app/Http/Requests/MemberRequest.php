<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->getKey();

        return [
            'memberNo' => ['required', 'string', 'max:30', Rule::unique('members', 'member_no')->ignore($memberId)],
            'name' => ['required', 'string', 'max:150'],
            'identity' => ['nullable', 'string', 'max:11'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'birthDate' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'emergencyName' => ['nullable', 'string', 'max:150'],
            'emergencyPhone' => ['nullable', 'string', 'max:30'],
            'membershipType' => ['required', 'string', 'max:80'],
            'durationMonths' => ['nullable', 'integer', 'min:0', 'max:600'],
            'validFrom' => ['nullable', 'date'],
            'validThrough' => ['nullable', 'date', 'after_or_equal:validFrom'],
            'paymentType' => ['nullable', Rule::in(['Nakit', 'Kredi Kartı', 'Havale'])],
            'contractAmount' => ['nullable', 'numeric', 'min:0'],
            'invoiceAddress' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['aktif', 'pasif'])],
        ];
    }
}
