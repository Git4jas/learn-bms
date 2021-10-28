<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'booking_id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'session_start_time',
        'session_end_time'
    ];

    /**
     * Get the consultation slots for the booking.
     */
    public function consultation_slots()
    {
        return $this->hasMany('App\Models\ConsultationSlot', 'booking_id', 'booking_id');
    }

    /**
     * Get the customer user that owns the booking.
     */
    public function customer_user()
    {
        return $this->belongsTo('App\Models\User', 'customer_user_id', 'user_id');
    }

    /**
     * Get the booking-status of the booking.
     */
    public function booking_status()
    {
        return $this->belongsTo('App\Models\BookingStatus', 'booking_status_id');
    }
}
