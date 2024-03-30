<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Support\Facades\DB;


class TimesheetController extends Controller
{
    public function get_timesheet_of_current_user(Request $request)
    {


        $now = new DateTime();
        // Get the first day of the month
        $now->modify('first day of this month');
        $start_of_month = $now->format('Y-m-d');

        // Get the last day of the month
        $now->modify('last day of this month');
        $end_of_month = $now->format('Y-m-d');

        $start_date =  $request->query('start_date') ?? $start_of_month;
        $end_date =  $request->query('end_date') ?? $end_of_month;
        $user = Auth::user();

        $rawSql = "
            WITH month_dates as(
                SELECT generate_series( ?::date,  ?::date , '1 day')::date as working_date
            ), 
            timesheet as (
                SELECT * from timesheets WHERE user_id =  ?
            )
            SELECT  md.working_date, ts.checkin_time, ts.checkout_time, ts.remarks, cd.day_type, cd.description
            FROM timesheets ts
            RIGHT JOIN month_dates md ON ts.working_date =  md.working_date
            LEFT JOIN calendars cd ON cd.date  =  md.working_date 
        ";

        $timesheet = DB::select($rawSql, [$start_date, $end_date, 1]);

        return response()->json(['data' => $timesheet, 'message' => 'sucess'], 200);
    }

    public function checked_in(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_date' => 'required|date_format:Y-m-d',
            'checkin_time' => 'required|date_format:H:i:s',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Get the current authenticated user
        $user = Auth::user();

        // Check if a timesheet record already exists for the given date
        $timesheet = Timesheet::where('user_id', $user->id)
            ->where('working_date', $request->working_date)
            ->first();

        // If a timesheet record exists, update it
        if ($timesheet) {
            $timesheet->update([
                'checkin_time' => $request->checkin_time,
            ]);
        } else {
            // Otherwise, create a new timesheet record
            $timesheet = Timesheet::create([
                'user_id' => $user->id,
                'working_date' => $request->working_date,
                'checkin_time' => $request->checkin_time,
            ]);
        }

        return response()->json(['message' => 'Checked In successfully'], 200);
    }

    public function checked_out(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_date' => 'required|date_format:Y-m-d',
            'checkout_time' => 'required|date_format:H:i:s',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Get the current authenticated user
        $user = Auth::user();

        // Check if a timesheet record already exists for the given date
        $timesheet = Timesheet::where('user_id', $user->id)
            ->where('working_date', $request->working_date)
            ->first();

        // If a timesheet record exists, update it
        if ($timesheet) {
            $timesheet->update([
                'checkout_time' => $request->checkout_time,
            ]);
        } else {
            return response()->json(['message' => 'Use has not checked in yet!!'], 400);
        }

        return response()->json(['message' => 'Checked In successfully'], 200);
    }

    public function get_today_status(Request $request)
    {
        $user  =  Auth::user();
        $today = date('Y-m-d');
        $timesheet = Timesheet::where('user_id', $user->id)
            ->where('working_date', $today)
            ->first();

        $status = 'UNCHECKED';

        if (!$timesheet) {
            $status =  'UNCHECKED';
            return response()->json(['message' => $status], 200);
        }

        if ($timesheet->checkin_time === null) {
            $status =  'UNCHECKED';
        } elseif ($timesheet->checkin_time !== null && $timesheet->checkout_time === null) {
            $status =  'CHECKED';
        } elseif ($timesheet->checkin_time !== null && $timesheet->checkout_time !== null) {
            $status =  'EOD';
        }
        return response()->json(['message' => $status], 200);
    }

    public function show_timesheet_page(Request $request)
    {

        return Inertia::render('Timesheet/Timesheet');
    }
}
