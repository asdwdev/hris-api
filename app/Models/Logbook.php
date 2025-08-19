<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = [
        'date',
        'today_task',
        'tomorrow_plan',
        'documentation',
        'constraints',
    ];
}
