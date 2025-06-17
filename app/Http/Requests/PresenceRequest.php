<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                $this->uniqueUserDateRule()
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'heure_arrivee' => [
                'required_if:absent,false',
                'nullable',
                'date_format:H:i',
                'before_or_equal:heure_depart',
                $this->arrivalTimeRule()
            ],
            'heure_depart' => [
                'required_with:heure_arrivee',
                'nullable',
                'date_format:H:i',
                'after:heure_arrivee'
            ],
            'minutes_retard' => [
                'nullable',
                'integer',
                'min:0',
                'max:300',
                'required_if:en_retard,true'
            ],
            'absent' => 'required|boolean',
            'en_retard' => [
                'required',
                'boolean',
                'exclude_if:absent,true'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.unique' => 'Cet utilisateur a déjà une entrée pour cette date.',
            'heure_arrivee.required_if' => "L'heure d'arrivée est requise quand l'étudiant est présent.",
            'heure_depart.after' => 'Le départ doit être après l\'arrivée.',
            'minutes_retard.required_if' => 'Veuillez spécifier le nombre de minutes de retard.',
            'minutes_retard.max' => 'Le retard ne peut excéder 300 minutes (5h).'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'absent' => $this->boolean('absent'),
            'en_retard' => $this->boolean('en_retard'),
            'date' => Carbon::parse($this->date)->format('Y-m-d')
        ]);
    }

    private function uniqueUserDateRule()
    {
        return Rule::unique('presences')
            ->where('user_id', $this->user_id)
            ->where('date', $this->date)
            ->ignore($this->presence);
    }

    private function arrivalTimeRule()
    {
        return function ($attribute, $value, $fail) {
            if ($value && $this->absent) {
                $fail("Incompatible : heure d'arrivée alors que marqué absent.");
            }
        };
    }
}
