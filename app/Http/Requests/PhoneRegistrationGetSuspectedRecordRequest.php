<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRegistrationGetSuspectedRecordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'bail|required|string',
            'time' => 'bail|required|date_format:Y-m-d\TH:i:s',
            'page' => 'bail|required|integer',
        ];
    }
}
