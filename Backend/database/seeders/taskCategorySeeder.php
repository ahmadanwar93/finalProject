<?php

namespace Database\Seeders;
use App\Models\TaskCategory;
use Illuminate\Database\Seeder;

class taskCategorySeeder extends Seeder
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
        for ($i = 0; $i < 20; $i++) {
            TaskCategory::create([
                'user_id' => 1,
                'category_stat' => $faker->numberBetween(0, 2),
                // 0 is deleted, 1 is default, 2 is important
                'category_desc' => $faker->text,
            ]);
        }
    }
}
