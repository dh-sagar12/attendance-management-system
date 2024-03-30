<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;

    protected $table  =  'applications';

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'description',
        'is_approved',
        'approved_by',
        'remarks',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
