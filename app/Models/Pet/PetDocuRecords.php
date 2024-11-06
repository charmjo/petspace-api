<?php

namespace App\Models\Pet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetDocuRecords extends Model
{
    use HasFactory;

    /**
     * @var int|mixed|string|null
     */

    protected $fillable = [
        'id',
        'pet_id',
        'added_by',
        'record_path',
        'date_added',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pet::class, 'user_id');
    }
}
