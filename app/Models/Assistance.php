<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Assistance extends Model
{
	use SoftDeletes;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assistances';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'assistance_id';

    /**
     * Get the user that owns the assistance.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Get the category that owns the assistance.
     */
    public function assistanceCategory()
    {
        return $this->belongsTo('App\Models\AssistanceCategory', 'assistance_category_id');
    }

    /**
     * Get bookings for the assitance.
     */
    public function bookings()
    {
        return $this->hasMany('App\Models\Booking', 'assistance_id');
    }
}
