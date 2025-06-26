<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
             'avatar' => [
                'nullable',
                File::image()
                    ->max(2048) // 2MB
                    ->dimensions(
                        Rule::dimensions()
                            ->maxWidth(2000)
                            ->maxHeight(2000)
                    ),
            ],
        ];
    }
}
