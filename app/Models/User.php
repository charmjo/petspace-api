<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
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

    // get family members using linking table
    // TODO: test this
    public static function getAllFamilyMembers () {
        // get authenticated user
        $parentId = Auth::id();

        // return all added members
        return DB::table('user_family as uf')
            ->leftJoin('users', 'uf.family_member_id', '=', 'users.id')
            ->select('uf.id as id'
                , 'users.first_name as first_name'
                , 'users.last_name as last_name'
                , 'users.email as email')
            ->where('uf.main_user_id', $parentId) // Only get active users
            ->get();
    }
}
