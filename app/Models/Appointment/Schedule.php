<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_booked',
        'updated_at'
    ];
    public function vet()  : BelongsTo
    {
        return $this->BelongsTo(Veterinarian::class, 'user_id');
    }

}
