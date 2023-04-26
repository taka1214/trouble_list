<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'nickname' => ['nullable','string', 'max:25'],
            'room_number' => ['string', 'max:4'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    
        if (empty($this->nickname)) {
            $rules['nickname'] = [
                function ($attribute, $value, $fail) {
                    if (empty($this->name)) {
                        $fail('The nickname or name field is required.');
                    } else {
                        $this->merge(['nickname' => $this->name]);
                    }
                },
            ];
        }
    
        return $rules;
    }
}
