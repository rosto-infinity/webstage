<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PresenceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriser toutes les requêtes
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('presences')->where(function ($query) {
                    return $query->whereDate('date', $this->date);
                })->ignore($this->route('id')), // Ignore lors de la mise à jour
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'heure_arrivee' => [
                'required_if:absent,false',
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if ($value && $this->absent) {
                        $fail("Incohérence : heure d'arrivée renseignée alors que marqué absent.");
                    }
                },
            ],
            'heure_depart' => [
                'required_with:heure_arrivee',
                'nullable',
                'date_format:H:i',
                'after:heure_arrivee',
                function ($attribute, $value, $fail) {
                    if ($value && ! $this->heure_arrivee) {
                        $fail("Le départ nécessite une heure d'arrivée.");
                    }
                    if ($value && $this->absent) {
                        $fail('Incohérence : heure de départ renseignée alors que marqué absent.');
                    }
                },
            ],
            'minutes_retard' => [
                'nullable',
                'integer',
                'min:0',
                'max:300',
                'required_if:en_retard,true',
                function ($attribute, $value, $fail) {
                    if ($value && $this->absent) {
                        $fail('Incohérence : retard renseigné alors que marqué absent.');
                    }
                },
            ],
            'absent' => 'required|boolean',
            'en_retard' => [
                'required',
                'boolean',
                'exclude_if:absent,true',
            ],
            'absence_reason_id' => [
                'required_if:absent,true',
                'nullable',
                'exists:absence_reasons,id',
                function ($attribute, $value, $fail) {
                    if ($value && ! $this->absent) {
                        $fail("Incohérence : motif d'absence renseigné alors que non absent.");
                    }
                },
            ],

        ];
    }

    public function messages()
    {
        return [
            'user_id.unique' => 'Cet étudiant a déjà une présence enregistrée pour cette date.',
            'date.before_or_equal' => 'La date ne peut pas être future.',
            'heure_arrivee.required_if' => "L'heure d'arrivée est obligatoire si non absent.",
            'minutes_retard.required_if' => 'Veuillez renseigner le nombre de minutes de retard.',
            'minutes_retard.max' => 'Le retard maximum autorisé est de 300 minutes (5h).',
            'heure_depart.after' => "L'heure de départ doit être postérieure à l'arrivée.",
            'heure_depart.required_with' => "L'heure de départ est requise quand l'heure d'arrivée est renseignée.",
            'absence_reason_id.required_if' => 'Un motif est obligatoire pour les absences.',
            'absence_reason_id.exists' => 'Le motif sélectionné est invalide.',

        ];
    }
}
