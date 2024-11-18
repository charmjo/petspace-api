<?php
// app/Enums/Status.php
namespace App\Enums;

enum AppointmentStatus: string
{
    case CONFIRMED = 'confirmed';
    case PENDING = 'pending';
    case RESCHEDULED = 'rescheduled';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';

}
