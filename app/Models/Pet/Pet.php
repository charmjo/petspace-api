<?php

namespace App\Models\Pet;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        'bio',
        'image_storage_path'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pet_owner_id');
    }

    public function docuRecords(): HasMany
    {
        return $this->HasMany(PetDocuRecords::class, 'pet_id');
    }

    public static function retrievePetAllergenList ($petId) : Collection {
        return DB::table('pet_allergy_record as par')
        ->leftJoin('pet_allergens as pa', 'pa.id', '=', 'par.allergen_id')
        ->leftJoin('users as user', 'user.id', '=', 'par.added_by')
        ->where('par.pet_id',$petId)
        ->select('par.id'
            , 'pa.id as allergen_id'
            , 'pa.allergen'
            ,'pa.classification'
            ,'user.first_name as added_by_first_name' 
            ,'user.last_name as added_by_last_name'
            ,'user.role as added_by_role'
            )
        ->get();
    }




}
