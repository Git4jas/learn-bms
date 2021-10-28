<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationSlot extends Model
{
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consultation_slots';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'consultation_slot_id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_time',
        'end_time'
    ];

    /**
     * Get the booking that owns the slot.
     */
    public function booking()
    {
        return $this->belongsTo('App\Models\Booking', 'booking_id', 'booking_id');
    }
}
