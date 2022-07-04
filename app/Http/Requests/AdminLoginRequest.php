<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
        return
            [
            'email'=>'required|max:255|email',
            'password'=>'required'
            ];
    }
    public function messages()
    {
        return
            [
                'email.required'=>'يجب ادخال الايميل',
                'email.max'=>'لا يجب ان يتعدي الايميل 255 حرف',
                'email.email'=>'يجب ادخال ايميل صحيح',
                'password.required'=>'يجب ادخال كلمة المرور ',
            ];
    }
}
