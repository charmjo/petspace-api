<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Account\Address;
use App\Models\Pet\Pet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function loadWithOtherModels () {
        $this->load('address')
            ->setRelation(
                'pets_count', 
                $this->pets()->count()
            );
    }

    // get family members using linking table
    // TODO: test this
    public static function getAllFamilyMembers ($parentId) {

        // return all added members
        return DB::table('user_family as uf')
            ->leftJoin('users', 'uf.family_member_id', '=', 'users.id')
            ->select('uf.id'
                , 'users.first_name'
                , 'users.last_name'
                , 'users.email')
            ->where('uf.main_user_id', $parentId) // Only get active users
            ->get();
    }
}
