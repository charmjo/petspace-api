<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    // pet will not be deleted permanently
    use  SoftDeletes, HasFactory;

    protected $fillable = [
        'pet_owner_id',
        'name',
        'breed',
        'animal_type',
        'dob',
        'color',
        'gender',
    ];
}
