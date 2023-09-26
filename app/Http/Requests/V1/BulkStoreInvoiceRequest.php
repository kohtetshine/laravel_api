<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            '*.customer_id' => ['required','integer'],
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required', Rule::in(['B','P','V','b','p','v'])],
            '*.billed_date' => ['required','date_format:Y-m-d H:i:s'],
            '*.paid_date' => ['date_format:Y-m-d H:i:s', 'nullable'],
        ];
    }

    // protected function propareForValidation(){
    //     $this->merge([
    //         'customer_id' => $this->customerId,
    //         'billed_date' => $this->billedDated,
    //         'paid_date' => $this->paidDate,
    //     ]);
    // }

    protected function propareForValidation(){
        $data=[];
        foreach ($$this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            $data[] = $obj;
        }
        $this->merge($data);
    }
}
