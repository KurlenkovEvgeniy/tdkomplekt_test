<?php

namespace Database\Factories;

use App\Enums\UserMaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        try {
            // method existence depends on faker_locale
            $middleName = $this->faker->middleName();
        }
        catch (\Exception $ex) {
            $middleName = '';
        }
        return [
            'surname' => $this->faker->lastName(),
            'name' => $this->faker->firstName(),
            'middle_name' => $middleName,
            'email' => $this->faker->unique()->safeEmail(),
            'marital_status' => UserMaritalStatus::randomValue(),
            'birthday' => $this->faker->date(),
            'about' => $this->faker->text(),
        ];
    }
}
