<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Contact;
use App\Repositories\ContactRepository;

class PhoneCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(ContactRepository $repository)
    {
        $contact = $repository->find($this->route('contact'));
        return $contact->user_id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phones' => 'array',
            'phones.*.number' => 'sometimes|required|string',
            'phones.*.label' => 'sometimes|required|string'
        ];
    }
}
