<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Role;
use App\Models\Assistance;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\ConsultationSlot;
use Carbon\Carbon;

class BookingSeeder extends Seeder
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

        $customers = User::whereHas('roles', function (Builder $query) {
            $query->where('roles.role_id', Role::CUSTOMER);
        })->get();

        $assistances = Assistance::select('assistance_id', 'cost_per_session')->get();

        foreach($customers as $customer) {
            foreach($assistances as $assistance) {
                for($i=0; $i<7; $i++) {
                    $booking = new Booking;
                    $booking->consultant_user_id = $consultant->user_id;
                    $booking->customer_user_id = $customer->user_id;
                    $booking->assistance_id = $assistance->assistance_id;
                    $booking->booking_status_id = BookingStatus::PENDING;
                    $booking->cost_per_session = $assistance->cost_per_session;
                    $booking->address_line_1 = $faker->streetAddress;
                    $booking->address_line_2 = NULL;
                    $booking->city = $faker->city;
                    $booking->state = $faker->stateAbbr;
                    $booking->zip = $faker->postcode;

                    $booking->save();

                    for($k=0; $k<2; $k++) {
                        $base_time = Carbon::now()->addDays(rand(4, 10));

                        $slot = new ConsultationSlot;
                        $slot->start_time = $base_time;
                        $slot->end_time = $base_time->addHours(2);
                        $slot->booking_id = $booking->booking_id;
                        $slot->save();
                    }
                }
            }
        }
    }
}
