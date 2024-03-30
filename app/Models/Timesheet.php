<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $table  =  'timesheets';

    protected $fillable = [
        'user_id',
        'application_type',
        'working_date',
        'checkin_time',
        'checkout_time',
        'remarks',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
