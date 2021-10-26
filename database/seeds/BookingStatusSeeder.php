<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('booking_statuses')->insert([
            [
                'booking_status_id' => 1,
                'label' => 'Pending',
                'slug' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'booking_status_id' => 2,
                'label' => 'Active',
                'slug' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'booking_status_id' => 3,
                'label' => 'Payment',
                'slug' => 'payment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
