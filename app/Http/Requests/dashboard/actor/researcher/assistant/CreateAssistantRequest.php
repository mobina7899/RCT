<?php

namespace App\Http\Requests\dashboard\actor\researcher\assistant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class CreateAssistantRequest extends FormRequest
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
            'range' => 'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z\s]+$/u',
            'n_number'=>['Required' , 'digits:10' , 'numeric', Rule::unique('researchers')->whereNull('deleted_at')],
            'name'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z\s]+$/u',
            'f_name'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z\s]+$/u',
            'major'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9.,\s]+$/u',
//            'proficiency'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9.,\s]+$/u',
            'university'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9.,\s]+$/u',
            'organization'=>'required|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9.,\s]+$/u',
            'username' => ['required' , 'string' , 'max:255' , Rule::unique('users') , 'regex:/^[a-zA-Z0-9]+$/u'],
            'email' => ['required' , 'email' , Rule::unique('users') , 'max:50'],
            'password' => ['required', Rules\Password::defaults()],

        ];
    }
}
