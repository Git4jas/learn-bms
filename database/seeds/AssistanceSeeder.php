<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
use Faker\Generator as Faker;
use App\Models\User;
use App\Models\AssistanceCategory;
use App\Models\Assistance;
use App\Models\Role;

class AssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $consultant = User::whereHas('roles', function (Builder $query) {
            $query->where('roles.role_id', Role::CONSULTANT);
        })->first();

        $categories = AssistanceCategory::all();
        
        foreach($categories as $category) {
            $assistance = new Assistance();
            $assistance->label = $faker->company;
            $assistance->assistance_category_id = $category->assistance_category_id;
            $assistance->user_id = $consultant->user_id;
            $assistance->description = $faker->paragraph(4);
            $assistance->cost_per_session = $faker->biasedNumberBetween(60, 100);
            $assistance->save();
        }
    }
}
