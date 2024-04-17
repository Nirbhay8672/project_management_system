<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|min:1|max:20|unique:users,username,' . $this->id,
            'first_name' => 'required|string|min:2|max:20',
            'last_name' => 'required|string|min:2|max:20',
            'email' => 'nullable|email|unique:users,email,' . $this->id,
            'password' => 'required_if:id,null|nullable|min:6',
            'confirm_password' => 'required_with:password|same:password',
        ];
    }

    public function fields(): array
    {
        $fields = [
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $fields['password'] = bcrypt($this->password);
        }

        return $fields;
    }

    public function action(): string
    {
        return is_null($this->id) ? 'created' : 'updated';
    }
}