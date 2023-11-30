<?php

namespace App\Modules\Account\Requests;

use App\Modules\Currency\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from_account_id' => ['required', 'numeric'],
            'to_account_id' => ['required', 'numeric'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'currency' => ['required', Rule::in(Currency::CURRENCIES)],
        ];
    }
}
