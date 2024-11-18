<?php

namespace App\Models\Appointment;

use App\Models\Account\ProfessionalInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Veterinarian extends User
{
    use HasFactory;

    public function professionalInformation() : HasOne
    {
        return $this->hasOne(ProfessionalInformation::class, 'user_id');
    }

    public function schedules() : HasMany
    {
        return $this->hasMany(Schedule::class, 'user_id');
    }

    public static function getAvailableSchedule ($vetId) : Collection {
        return DB::table('users')
            ->leftJoin('schedules as sched', 'users.id', '=', 'sched.user_id')
            ->where('users.role', 'veterinarian')
            ->where('users.id', $vetId)
            ->where('sched.is_booked', 0)
            ->select('users.last_name', 'users.first_name', 'sched.*')
            ->get();
    }
    public static function getAvailableVeterinarians () : Collection {
        return  DB::table('users')
            ->leftJoin('professional_information as prof_info', 'users.id', '=', 'prof_info.user_id')
            ->where('users.role', 'veterinarian')
            ->where('prof_info.is_verified', true)
            ->whereRaw('(SELECT COUNT(*) FROM schedules WHERE is_booked = 0) > 0')
            ->select('users.id', 'users.first_name', 'users.last_name', 'prof_info.professional_title')
            ->get();
    }

}
