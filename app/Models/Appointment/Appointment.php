<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'request_date',
        'confirmed_at',
        'pet_id',
        'schedule_id',
        'notes'
    ];

}
