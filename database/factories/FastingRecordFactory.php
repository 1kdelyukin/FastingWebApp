<?php

namespace Database\Factories;

use App\Models\FastingRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class FastingRecordFactory extends Factory
{
    protected $model = FastingRecord::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Example: each record belongs to user_id = 1 by default
        // or you can randomize the user if you want.
        
        return [
            'user_id' => 1, 
            // Random date within the last 30 days
            // Random daily total fasting minutes between 50 and 1200 (up to 20 hours)
            'total_fasting_minutes' => $this->faker->numberBetween(50, 1600),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
