<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PresenceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $today = Carbon::today()->toDateString();

        return [
            // Validation de l'utilisateur et unicité par date
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('presences')
                    ->where(function ($query) {
                        return $query->whereDate('date', $this->date);
                    })
                    ->ignore($this->presence), // Compatible avec route model binding
            ],

            // Validation de la date
            'date' => [
                'required',
                'date',
                'before_or_equal:'.$today,
            ],

            // Gestion conditionnelle des horaires
            'heure_arrivee' => [
                'required_unless:absent,true',
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if ($this->absent && $value) {
                        $fail('Les horaires doivent être vides quand absent.');
                    }
                },
            ],

            'heure_depart' => [
                'required_with:heure_arrivee',
                'nullable',
                'date_format:H:i',
                'after:heure_arrivee',
                function ($attribute, $value, $fail) {
                    if ($value && $this->absent) {
                        $fail('Les horaires doivent être vides quand absent.');
                    }
                },
            ],

            // Gestion du retard
            'minutes_retard' => [
                'nullable',
                'integer',
                'min:0',
                'max:300',
                'required_if:en_retard,true',
                function ($attribute, $value, $fail) {
                    if ($value && $this->absent) {
                        $fail('Le retard doit être vide quand absent.');
                    }
                    if ($value && ! $this->heure_arrivee) {
                        $fail("Un retard nécessite une heure d'arrivée.");
                    }
                },
            ],

            // Statuts
            'absent' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value && ($this->heure_arrivee || $this->en_retard)) {
                        $fail('Désactivez les horaires/retard avant de marquer absent.');
                    }
                },
            ],

            'en_retard' => [
                'required',
                'boolean',
                'exclude_if:absent,true',
                function ($attribute, $value, $fail) {
                    if ($value && ! $this->heure_arrivee) {
                        $fail("Un retard nécessite une heure d'arrivée.");
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            // Messages personnalisés
            'user_id.unique' => 'Une présence existe déjà pour cet étudiant à cette date.',
            'date.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'heure_arrivee.required_unless' => "L'heure d'arrivée est obligatoire sauf si absent.",
            'minutes_retard.required_if' => 'Le nombre de minutes de retard est obligatoire.',
            'heure_depart.after' => "L'heure de départ doit être après l'arrivée.",
            'heure_depart.required_with' => "L'heure de départ est requise avec l'arrivée.",
        ];
    }

    public function prepareForValidation()
    {
        // Nettoyage des données avant validation
        $this->merge([
            'en_retard' => $this->en_retard && ! $this->absent,
            'minutes_retard' => $this->absent ? null : $this->minutes_retard,
        ]);
    }
}
