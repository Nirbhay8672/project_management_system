<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'up_or_down' => 'required',
            'project_name' => 'required|string|unique:projects,project_name',
            'project_logo' => 'required|file|mimes:jpg,png|max:500000',
            'project_url' => 'required|string',
            'google_rank' => 'required|numeric',
            'time' => 'required|numeric',
            'total_update' => 'required|numeric',
            'is_backup_active' => 'required|numeric',
            'total_site_helth' => 'required|numeric',
            'total_php_issue' => 'required|numeric',
            'wp_admin_url' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ]));
    }
}