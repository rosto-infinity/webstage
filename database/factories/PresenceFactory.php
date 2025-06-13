<?php

namespace Database\Factories;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Presence>
 */
class PresenceFactory extends Factory
{
    protected $model = Presence::class;

    public function definition(): array
    {
        // Sélection d'un utilisateur existant, sinon création
        $user = User::inRandomOrder()->first() ?: User::factory()->create();

        $isAbsent = $this->faker->boolean(10);
        $isLate = !$isAbsent && $this->faker->boolean(20);

        $arrival = $isAbsent ? null : $this->faker->time('H:i:s', '08:30');
        $departure = $isAbsent ? null : $this->faker->time('H:i:s', '17:30');
        $lateMinutes = $isLate ? $this->faker->numberBetween(1, 60) : 0;

        return [
            'user_id'         => $user->id,
            'date'            => $this->faker->dateTimeBetween('-1 month','now')->format('Y-m-d'),
            'arrival_time'    => $arrival,
            'departure_time'  => $departure,
            'late_minutes'    => $lateMinutes,
            'absent'          => $isAbsent,
            'late'            => $isLate,
        ];
    }
}
