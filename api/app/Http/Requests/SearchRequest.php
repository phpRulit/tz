<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text' => 'nullable|string',
            'from' => 'nullable|integer',
            'to' => 'nullable|integer',
        ];
    }

}
