<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'max:32', 'confirmed'],
            'name'     => ['required', 'min:2', 'max:32'],
        ];
    }
}
