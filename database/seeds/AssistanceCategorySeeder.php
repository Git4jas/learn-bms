<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AssistanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assistance_categories')->insert([
            [
                'label' => 'Language Training',
                'slug' => 'language-training',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'label' => 'Training & Fitness',
                'slug' => 'training-fitness',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
