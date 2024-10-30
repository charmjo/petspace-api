<?php

namespace App\Models\Pet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pet_owner_id');
    }
}
