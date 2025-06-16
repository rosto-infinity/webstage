<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PresenceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Autoriser tous les utilisateurs (à adapter selon vos besoins)
    }

    public function rules()
    {
        $today = Carbon::now()->format('Y-m-d');

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('presences')->where(function ($query) {
                    return $query->where('date', $this->date);
                })->ignore($this->presence) // Pour les updates
            ],
            'date' => [
                'required',
                'date',
                'before_or_equal:'.$today
            ],
            'heure_arrivee' => [
                'nullable',
                'date_format:H:i',
                function ($attr, $value, $fail) {
                    if ($value && $this->absent) {
                        $fail("Une heure d'arrivée est incompatible avec une absence.");
                    }
                }
            ],
            'heure_depart' => [
                'nullable',
                'date_format:H:i',
                'after:heure_arrivee',
                'required_with:heure_arrivee'
            ],
            'minutes_retard' => [
                'nullable',
                'integer',
                'min:0',
                'max:300', // 5h max de retard
                'required_if:en_retard,true'
            ],
            'absent' => 'required|boolean',
            'en_retard' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'user_id.unique' => 'Une entrée existe déjà pour cet utilisateur à cette date.',
            'heure_depart.after' => 'Le départ doit être après l\'arrivée.',
            'minutes_retard.required_if' => 'Le nombre de minutes est requis quand "en retard" est coché.'
        ];
    }

    // Préparation des données avant validation
    protected function prepareForValidation()
    {
        $this->merge([
            'absent' => $this->boolean('absent'),
            'en_retard' => $this->boolean('en_retard')
        ]);
    }
}
