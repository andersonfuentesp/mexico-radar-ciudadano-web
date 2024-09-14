<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(), // Usar firstName() para generar nombres
            'lastname' => $this->faker->lastName(), // Generar apellidos
            'username' => $this->faker->unique()->userName(),
            'image' => 'https://picsum.photos/640/480?random=' . $this->faker->unique()->numberBetween(1, 1000),
            'email' => $this->faker->unique()->safeEmail(),
            'date_register' => $this->faker->date(), // Generar una fecha aleatoria
            'last_connection' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'), // Generar una fecha y hora aleatorias de este mes
            'email_verified_at' => now(),
            'password' => Hash::make('12345'), // Contraseña hasheada
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
