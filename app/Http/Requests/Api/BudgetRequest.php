<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'budget_line' => 'required|array|min:1',
            'budget_line.*.net_amount' => 'required|numeric',
            'budget_line.*.vat' => 'required|numeric|min:0|max:100'
        ];
    }
}
