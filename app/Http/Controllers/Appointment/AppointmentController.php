<?php

namespace App\Http\Controllers\Appointment;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\Schedule;
use App\Models\Appointment\Veterinarian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pet/{petId}/appointment/veterinarians",
     *     summary="Get list of veterinarians for a pet appointment",
     *     description="Retrieves a list of veterinarians available for a specific pet's appointment.",
     *     operationId="getVeterinarians",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         required=true,
     *         description="The ID of the pet to fetch the veterinarians for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A list of veterinarians for the pet's appointment",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", description="Veterinarian ID"),
     *                 @OA\Property(property="first_name", type="string", description="Veterinarian's first name"),
     *                 @OA\Property(property="last_name", type="string", description="Veterinarian's last name"),
     *                 @OA\Property(property="professional_title", type="string", description="Veterinarian's professional title")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid pet ID supplied"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Pet not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error"
     *     )
     * )
     */
    public function getAvailableVeterinarian () {
        return response()->json(Veterinarian::getAvailableVeterinarians());
    }

    // get available schedules
    /**
     * @OA\Get(
     *     path="pet/{petId}/appointment/vet-schedule/{scheduleId}",
     *     summary="Get veterinarian schedule for a pet appointment",
     *     description="Retrieves the details of a specific veterinarian's schedule for a pet appointment, including service provider, schedule time, and cost.",
     *     operationId="getVeterinarianSchedule",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="petId",
     *         in="path",
     *         required=true,
     *         description="The ID of the pet to fetch the veterinarian schedule for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="scheduleId",
     *         in="path",
     *         required=true,
     *         description="The ID of the veterinarian schedule",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A list of available veterinarian schedules for the pet's appointment",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="id", type="integer", description="The unique ID of the veterinarian schedule"),
     *                     @OA\Property(property="user_id", type="integer", description="The ID of the user (veterinarian)"),
     *                     @OA\Property(property="service_company_provider_name", type="string", description="The name of the veterinary service provider"),
     *                     @OA\Property(property="schedule_date", type="string", format="date", description="The date of the appointment"),
     *                     @OA\Property(property="start_time", type="string", format="time", description="The start time of the appointment"),
     *                     @OA\Property(property="end_time", type="string", format="time", description="The end time of the appointment"),
     *                     @OA\Property(property="is_booked", type="boolean", description="Whether the appointment is booked"),
     *                     @OA\Property(property="description", type="string", description="The description of the appointment"),
     *                     @OA\Property(property="cost", type="number", format="float", description="The cost of the appointment"),
     *                     @OA\Property(property="location", type="string", description="The location of the veterinary service provider"),
     *                     @OA\Property(property="service_type", type="string", description="The type of veterinary service (e.g., checkup, emergency care)"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", description="When the schedule was created"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", description="When the schedule was last updated")
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid pet ID or schedule ID supplied"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Pet or schedule not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error"
     *     )
     * )
     */
    public function getAvailableSchedule ($vetId) {
        return response()->json(Veterinarian::getAvailableSchedule($vetId));
    }

    // create appointment

    // TODO: Ideally I do not need to check if the appointment is already booked but this is an api so I need to handle this

    /**
     * @OA\Post(
     *     path="/api/pet/{pet_id}/appointment",
     *     summary="Create an appointment",
     *     description="Creates or updates an appointment for a pet, including notes and schedule.",
     *     operationId="createOrUpdateAppointment",
     *     tags={"appointments"},
     *     @OA\Parameter(
     *         name="pet_id",
     *         in="path",
     *         description="ID of the pet for which the appointment is being created",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"schedule_id", "notes"},
     *                 @OA\Property(property="schedule_id", type="integer", description="The ID of the schedule"),
     *                 @OA\Property(property="notes", type="string", description="Additional notes for the appointment")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment created/updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="The ID of the appointment"),
     *             @OA\Property(property="status", type="string", description="The status of the appointment (confirmed)"),
     *             @OA\Property(property="schedule_id", type="integer", description="The ID of the associated schedule"),
     *             @OA\Property(property="request_date", type="string", format="date-time", description="The request date and time of the appointment"),
     *             @OA\Property(property="confirmed_at", type="string", format="date-time", description="The confirmation date and time"),
     *             @OA\Property(property="canceled_at", type="string", format="date-time", description="The cancellation date and time, if applicable"),
     *             @OA\Property(property="notes", type="string", description="The notes for the appointment"),
     *             @OA\Property(property="provider_first_name", type="string", description="The first name of the provider"),
     *             @OA\Property(property="provider_last_name", type="string", description="The last name of the provider"),
     *             @OA\Property(property="service_company_provider_name", type="string", description="The name of the service provider company"),
     *             @OA\Property(property="location", type="string", description="The location of the appointment"),
     *             @OA\Property(property="schedule_date", type="string", format="date", description="The scheduled date of the appointment"),
     *             @OA\Property(property="start_time", type="string", format="time", description="The start time of the appointment"),
     *             @OA\Property(property="end_time", type="string", format="time", description="The end time of the appointment"),
     *             @OA\Property(property="service_type", type="string", description="The type of service")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input, appointment could not be created"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pet or schedule not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function createAppointment ($petId, Request $request) {
        /* I would need the following:
         * To set in server = userid in auth, the status, request_date, request_updated_date
         * confirmed_at
         * canceled at (should I let request_updated_date handle this)?
         * */
        // TODO: check if appointment exists
        // TODO: ownership check

        $authUserId = Auth::id();
        $appointmentData = array_merge($request->all(),
            [
                'pet_id' => $petId,
                'user_id' => $authUserId,
                'status' => AppointmentStatus::CONFIRMED, // the reason I set this since this is just create.
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

        DB::beginTransaction();
        // Perform multiple operations
        // first is find the sched id
        $sched = Schedule::find($schedId);

        if ($sched->is_booked) {
            DB::rollBack();
            return response()->json(
                ['message'=>'Schedule is already booked'],409
            );
        }

        //write to appointments table
        $appointment = new Appointment($appointmentData);
        $appointment->save();


        // update schedules table
        $sched->update($scheduleData);
        // retrieve
        //$addedSchedId = $appointment->id;

        DB::commit();

        $appointmentDetail = Appointment::getAppointment($appointment->id);

        return response()->json($appointmentDetail,201);
    }

    // change status of schedule and appointment,
    // should I set this to cancel

    public function cancelAppointment ($petId,$appointmentId) {
        // TODO: check if appointment exists
        // TODO: ownership check

        $authUserId = Auth::id();
        $appointmentData =
            [
                'pet_id' => $petId,
                'user_id' => $authUserId,
                'status' => AppointmentStatus::CANCELED, // the reason I set this since this is just create.
                'updated_at' => now(),
                'cancelled_at' => now()
            ];

        /*my pseudocode
        - first set what needs to be set in the server
        - insert appointmentData to appointments table and update schedule here
        - so, will need to put this in a transaction because I am editing two tables
        -TODO: just post directly. unya na ni butangi og guardrails
        */

        DB::transaction(
            function () use ($appointmentData, $appointmentId) {
                // update appointment table status to cancel
                $appointment = Appointment::where('id', $appointmentId)->first();
                $appointment->update($appointmentData);

                // first is find the sched id
                $sched = Schedule::find($appointment->schedule_id);
                $sched->update([
                    'is_booked' => false,
                    'updated_at' => now()
                ]);
            }
        );
        return response()->json(['message'=>'Appointment cancelled successfully'],200);

    }

    public function rescheduleAppointment (Request $request,$petId, $appointmentId)  {

        // I need the id of the new schedule and id of the old schedule
        // get the id
        // check if note is present, if it is, then add to update
        $validator = Validator::make($request->all(),[
            'new_schedule_id' => ['required','integer'],
            'old_schedule_id' => ['required','integer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // TODO: check if appointment is booked

        // TRANSACTION PSEUDOCODE STARTS HERE
        // update updated_date and schedule id in the appointments
        $appointmentData = [
            'schedule_id' => $request->input('new_schedule_id'),
            'status' => AppointmentStatus::CONFIRMED, // the reason I set this since this is just create.
            'request_date' => now(),
            'confirmed_at' => now()
        ];

        // update is_booked to FALSE and updated date of old schedule
        DB::beginTransaction();
        //write to appointments table
        $appointment = Appointment::where('id', $appointmentId)->first();
        $appointment->update($appointmentData);

        //  update old schedule
        $oldSched = Schedule::find($request->input('old_schedule_id'));
        $oldSched->update([
            'is_booked' => false,
            'updated_at' => now()
        ]);

        // update new sched id
        $newSched = Schedule::find($request->input('new_schedule_id'));

        if ($newSched->is_booked) {
            DB::rollBack();
            return response()->json(
                ['message'=>'Schedule is already booked'],409
            );
        }

        $newSched->update([
            'is_booked' => true,
            'updated_at' => now()
        ]);

        DB::commit();

        $appointmentDetail = Appointment::getAppointment($appointmentId);

        return response()->json([
            'message' => 'Appointment rescheduled successfully',
            $appointmentDetail
        ],200);
    }

    // upcoming and done appointments
    public function getAppointmentList ($petId) : JsonResponse {
        // return needed: doctor_name, service type, date, location, image
        // tables: appointments, schedule, user
        // this is a pet owner query
        $petOwnerId = Auth::id();

        $appointmentList = Appointment::getAppointmentList($petOwnerId,$petId);

        return response()->json(
            [
                'message' => 'Appointment list retrieved successfully',
                'list' => $appointmentList
            ]
        );

    }


    public function getAppointment($petId,$appointmentId) {
        // return needed: doctor_name, service type, date, location, image
        // tables: appointments, schedule, user
        $appointmentDetail = Appointment::getAppointment($appointmentId);

        return response()->json($appointmentDetail,200);

    }
}
