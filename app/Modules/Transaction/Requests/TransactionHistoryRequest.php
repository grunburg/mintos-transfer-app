<?php

namespace App\Modules\Transaction\Requests;

use App\Modules\Currency\Enums\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => ['numeric'],
            'offset' => ['numeric'],
        ];
    }
}
