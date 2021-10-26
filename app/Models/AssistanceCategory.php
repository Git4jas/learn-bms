<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistanceCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'assistance_categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'assistance_category_id';
}
