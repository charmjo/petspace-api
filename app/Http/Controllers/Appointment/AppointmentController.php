<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\Schedule;
use App\Models\Appointment\Veterinarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Laravel\Prompts\confirm;

class AppointmentController extends Controller
{
    // get available doctor
    public function getAvailableVeterinarian () {
        return response()->json(Veterinarian::getAvailableVeterinarians());
    }

    // get available schedules
    public function getAvailableSchedule ($vetId) {
        return response()->json(Veterinarian::getAvailableSchedule($vetId));
    }

    // create appointment

    // for this thing, I need to set this to
    public function createAppointment (Request $request) {
        /* I would need the following:
         * To set in server = userid in auth, the status, request_date, request_updated_date
         * TODO: I will not do appointment_date and appointment_time because I will get it from the schedules table
         * confirmed_at
         * canceled at (should I let request_updated_date handle this)?
         * */


        $authUserId = Auth::id();

        $appointmentData = array_merge($request->all(),
            [
                'user_id' => $authUserId,
                'status' => 'confirmed', // the reason I set this since this is just create.
                'request_date' => now(),
                'confirmed_at' => now()
            ]
        );

        $scheduleData = [
            // update the date, is booked
            'is_booked' => true,
            'updated_at' => now()
        ];

        $schedId = $request->input('schedule_id');

        /*my pseudocode
        - first set what needs to be set in the server
        - insert appointmentData to appointments table and update schedule here
        - so, will need to put this in a transaction because I am editing two tables
        -TODO: just post directly. unya na ni butangi og guardrails
        */

        $transactionData = DB::transaction(
            function () use ($appointmentData, $scheduleData,$request,$schedId) {
                // Perform multiple operations
                // first is find the sched id
                $sched = Schedule::find($schedId);
                //write to appointments table

                $appointment = new Appointment($appointmentData);
                $appointment->save();

                // update schedules table
                $sched->update($scheduleData);
//                // retrieve

                // Return computed data
//                return [
//                    'userCount' => $userCount,
//                    'totalRevenue' => $totalRevenue,
//                ];
        });
    }

    // change status of schedule and appointment,
    // should I set this to cancel
    public function changeStatusToCancel ($id) {
        // I will use another transaction
        // change appointment entry to cancel
        // fill the cancelled_at date
        // set is_booked to 1
    }

    public function rescheduleAppointment ($id)  {
        // I need the id of the new schedule and id of the old schedule
        // giatay aning filters, sorry y'all you will have to bear this
        // get the id

        // TRANSACTION PSEUDOCODE STARTS HERE
        // update updated_date and schedule id in the appointments
        // update is_booked to FALSE and updated date of old schedule
        // update is_booked to TRUE and updated date of new schedule
    }

    public function getAppointmentList () {
        // return needed: doctor_name, service type, date, location, image
        // tables: appointments, schedule, user


    }

    public function getAppointment() {
        // return needed: doctor_name, service type, date, location, image
        // tables: appointments, schedule, user


    }


}
