<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDoctorRequest extends FormRequest
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
//            'name' => 'required,unique:doctors,name',
//            'specialty_id' => 'required',
//            'location_id' => 'required',
//            'address' => 'required',
//            'visit_price' => 'required,numeric',
//            'bio' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
//            'name.required' => 'The doctor\'s name is required.',
//            'name.unique' => 'A doctor with this name already exists.',
//            'specialty_id.required' => 'Please select a specialty for the doctor.',
//            'location_id.required' => 'Please provide the location ID.',
//            'address.required' => 'The address field is required.',
//            'visit_price.required' => 'Please specify the visit price.',
//            'visit_price.numeric' => 'Visit price must be a number.',
//            'bio.required' => 'The doctor\'s bio is required.'
        ];
    }
}
