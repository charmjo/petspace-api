<?php

namespace App\Models\Appointment;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getAppointmentList ($petOwnerId, $petId) {
        $query = DB::table('appointments as app')
            ->select([
                'app.id',
                'app.status',
                'app.schedule_id',
                'app.request_date',
                'app.confirmed_at',
                'app.canceled_at',
                'app.notes',
                'app.created_at',
                'app.updated_at',
                DB::raw('(SELECT first_name FROM users WHERE id = schedules.user_id) as provider_first_name'),
                DB::raw('(SELECT last_name FROM users WHERE id = schedules.user_id) as provider_last_name'),
                'schedules.service_company_provider_name',
                'schedules.location',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.service_type',
            ])
            ->leftJoin('users', 'app.user_id', '=', 'users.id')
            ->leftJoin('schedules', 'app.schedule_id', '=', 'schedules.id')
            ->leftJoin('pets', 'app.pet_id', '=', 'pets.id')
            ->where('app.user_id', $petOwnerId)
            ->where('app.pet_id', $petId);

        return $query->get();
    }

    public static function getAppointment ($appointmentId) {
        $query = DB::table('appointments as app')
            ->select([
                'app.id',
                'app.status',
                'app.schedule_id',
                'app.request_date',
                'app.confirmed_at',
                'app.canceled_at',
                'app.notes',
                'app.created_at',
                'app.updated_at',
                DB::raw('(SELECT first_name FROM users WHERE id = schedules.user_id) as provider_first_name'),
                DB::raw('(SELECT last_name FROM users WHERE id = schedules.user_id) as provider_last_name'),
                'schedules.service_company_provider_name',
                'schedules.location',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.service_type',
            ])
            ->leftJoin('users', 'app.user_id', '=', 'users.id')
            ->leftJoin('schedules', 'app.schedule_id', '=', 'schedules.id')
            ->leftJoin('pets', 'app.pet_id', '=', 'pets.id')
            ->where('app.id', $appointmentId);

        return $query->first();
    }


}
