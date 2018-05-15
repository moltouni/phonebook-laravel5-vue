<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Entities\Contact;
use App\Repositories\ContactRepository;

class ContactUpdateRequest extends FormRequest
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
            'first_name' => 'string',
            'last_name' => 'string',
            'favorite' => 'boolean',
            'email' => 'string',
            'avatar' => 'image|max:2048',
        ];
    }
}
