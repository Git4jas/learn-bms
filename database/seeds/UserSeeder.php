<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = [
            [
                'name' => $faker->name,
                'email' => 'consultant@example.com',
                'password' => Hash::make('password12345'),
                'role_id' => Role::CONSULTANT
            ],
            [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make(Str::random(8)),
                'role_id' => Role::CUSTOMER
            ],
            [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make(Str::random(8)),
                'role_id' => Role::CUSTOMER
            ],
        ];

        foreach($users as $user_info) {
            $user = new User();
            $user->name = $user_info['name'];
            $user->email = $user_info['email'];
            $user->password = $user_info['password'];
            $user->address_line_1 = $faker->streetAddress;
            $user->address_line_2 = NULL;
            $user->city = $faker->city;
            $user->state = $faker->stateAbbr;
            $user->zip = $faker->postcode;

            $user->save();

            $user->roles()->attach($user_info['role_id']);
        }
    }
}
