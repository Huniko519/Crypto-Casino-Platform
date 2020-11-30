<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
        // $this->user = user being checked
        // $this->user() = currently logged in user
        return [
            'name'  => 'required|string|min:3|max:100|unique:users,name,' . $this->user()->id . ',id', // don't validate name uniqueness if it's not updated
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id . ',id', // don't validate email uniqueness if it's not updated
        ];
    }
}
