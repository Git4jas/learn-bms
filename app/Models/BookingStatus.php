<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    const PENDING = 1;
    const ACTIVE = 2;
    const PAYMENT = 3;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'booking_statuses';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'booking_status_id';
}
