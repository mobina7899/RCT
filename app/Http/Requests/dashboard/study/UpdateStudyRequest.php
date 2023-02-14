<?php

namespace App\Http\Requests\dashboard\study;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudyRequest extends FormRequest
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

            'title' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z\s]+$/u|max:255',
            'name' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651} a-zA-Z\s]+$/u|max:255',
            'studies_type' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z\s]+$/u',
            'study_design' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9\s]+$/u',
            'file' => 'nullable|mimes:doc,xlx,pdf,scv,xls,docx,',
            'start_date' => 'nullable|sometimes',
            'end_date' => 'nullable|sometimes|after:start_date',
            'purpose_study' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9\s]+$/u',
            'phase_study' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9\s]+$/u',
            'randomization' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9\s]+$/u',
            'blinding' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9\s]+$/u',
            'study_specifications' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9?!.\s,\[\]\"]+$/u',
            'entry_study' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9?!.\s,\[\]\"]+$/u',
            'failure_entry_study' => 'nullable|regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9?!.\s,\[\]\"]+$/u',
            'start_date_illness' => 'sometimes|after:dates',
            'end_date_illness' => 'sometimes|after:start_date_illnes',
            'termination_illness' => 'nullable|sometimes',
            'start_get_sick_ended' => 'sometimes|before:dates',
            'end_get_sick_ended' => 'sometimes|after:start_get_sick_ended|before:dates',
            'gender' => 'nullable|numeric',
            'min_year' => 'digits_between:1,3|gte:0' ,
            'min_month' => 'digits_between:1,3|gte:0' ,
            'min_day' => 'digits_between:1,3|gte:0' ,
            'max_year' => 'digits_between:1,3|gte:0' ,
            'max_month' => 'digits_between:1,3|gte:0' ,
            'max_day' => 'digits_between:1,3|gte:0' ,
            'tags' => ['nullable' , 'regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9,\s_\[\]\"]+$/u'],
            'intervention' => ['nullable' , 'regex:/^[\x{600}-\x{6FF}\x{200c}\x{064b}\x{064d}\x{064c}\x{064e}\x{064f}\x{0650}\x{0651}a-zA-Z0-9,\-\[\]":\{\}\s_]+$/u'],

        ];
    }
}
