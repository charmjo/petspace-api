<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Account\Address;
use App\Models\Account\ProfessionalInformation;
use App\Models\Pet\Pet;
use App\Models\Pet\PetDocuRecords;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
// Commented as to be implemented later
// implements MustVerifyEmail
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'role',
        'dob',
        'gender',
        'password',
        'is_form_filled',
        'phone',
        'avatar_storage_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function pets(): HasMany
    {
        return $this->hasMany(Pet::class,'pet_owner_id');
    }

    public function docuRecords(): HasMany
    {
        return $this->hasMany(PetDocuRecords::class,'user_id');
    }

    public function professionalInformation() : HasOne
    {
        return $this->hasOne(ProfessionalInformation::class, 'vet_id'); // Assuming `vet_id` is the foreign key in `professional_information` table
    }

    public function loadWithOtherModels () {
        $this->load('address')
            ->setRelation(
                'pets_count',
                $this->pets()->count()
            );
    }

    // get family members using linking table
    // TODO: test this
    public static function getAllFamilyMembers ($parentId) : Collection {

        // return all added members
        $results = DB::table('user_family as uf')
            ->leftJoin('users', 'uf.family_member_id', '=', 'users.id')
            ->select('uf.id'
                , 'users.first_name as first_name'
                , 'users.last_name as last_name'
                , 'users.email as email'
                ,'users.avatar_storage_path as profile_image')
            ->where('uf.main_user_id', $parentId) // Only get active users
            ->get();

        // TODO: add the storage file conversion as a util. THIS DOES NOT NEED TO BE A CONTROLLER
        $results = $results->map(function ($item) {
            $pathToFile = $item->profile_image;
            $temporaryUrl = $pathToFile ? Storage::temporaryUrl($pathToFile, now()->addHour(1)) : null;

            $item->profile_image=$temporaryUrl;
            return $item;
        });

        return $results;
    }
}
