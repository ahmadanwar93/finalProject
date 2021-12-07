<?php

namespace Database\Seeders;

use App\Models\Tasks;
use Illuminate\Database\Seeder;

class taskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
    Tasks::create([
                'cat_id' => $faker->numberBetween(7, 9),
                // 0 is deleted, 1 is default, 2 is important
                'task_stat' => $faker->numberBetween(0, 2),
                'task_desc' => $faker->text,
            ]);
        }
    }
}
