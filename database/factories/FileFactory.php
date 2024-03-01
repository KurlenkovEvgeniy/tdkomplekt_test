<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = $this->faker->randomElement([
            User::class,
        ]);
        return [
            'model_type' => $model,
            'model_id' => $model::factory(),
            'name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
            'file_name' => $this->faker->filePath(),
            'mime_type' => $this->faker->mimeType(),
            'size' => $this->faker->randomNumber(7, true),
            'collection_name' => $this->faker->word(),
        ];
    }
}
