<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\YearlyLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function admin_index()
    {
        $leaves = LeaveApplication::with('user.userBasicInfo')
            ->where('statuz', '!=', 'new')
            ->get();

        $currentYear = Carbon::now()->year;
        $annual = LeaveApplication::where('leave_type','annual')->whereYear('created_at',$currentYear)->count();
        $medical = LeaveApplication::where('leave_type','medical')->whereYear('created_at',$currentYear)->count();
        $other = LeaveApplication::where('leave_type','other')->whereYear('created_at',$currentYear)->count();
        $compassionate = LeaveApplication::where('leave_type','compassionate')->whereYear('created_at',$currentYear)->count();

        $data['annual'] = $annual;
        $data['medical'] = $medical;
        $data['other'] = $other;
        $data['compassionate'] = $compassionate;
        $data['pagetitle'] = 'Admin Leaves Management';
        $data['leaves'] = $leaves;
        return view('hr.admin_index', ['data' => $data]);
    }

    public function manager_index()
    {
        $leaves = LeaveApplication::with('user.userBasicInfo')->get();

        $data['pagetitle'] = 'Hr/Managers Leaves Management';
        $data['leaves'] = $leaves;
        return view('hr.leave', ['data' => $data]);
    }
    public function leave_application()
    {
        $currentYear = Carbon::now()->year;
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $yearly_leave_days = YearlyLeave::where('user_id',$user_id)->where('year',$currentYear)->first();
        $data['pagetitle'] = 'Leaves Management';
        $data['leave_days'] = $yearly_leave_days;
        return view('hr.leave_form', ['data' => $data]);
    }


    public function user_index()
    {
        $currentYear = Carbon::now()->year;
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;

        $leaves =  LeaveApplication::where('user_id', $user_id)->get();
        $annual = LeaveApplication::where('user_id', $user_id)->where('leave_type','annual')->count();
        $medical = LeaveApplication::where('user_id', $user_id)->where('leave_type','medical')->count();
        $other = LeaveApplication::where('user_id', $user_id)->where('leave_type','other')->count();
        $compassionate = LeaveApplication::where('user_id', $user_id)->where('leave_type','compassionate')->count();
        $yearly_leave_days = YearlyLeave::where('user_id',$user_id)->where('year',$currentYear)->first();
        $data['pagetitle'] = 'Leaves Management';
        $data['leave_days'] = $yearly_leave_days;
        $data['leaves'] = $leaves;
        $data['annual'] = $annual;
        $data['medical'] = $medical;
        $data['other'] = $other;
        $data['compassionate'] = $compassionate;
        return view('hr.user_index', ['data' => $data]);
    }


    public function user_edit_index($id)
    {
        $currentYear = date('Y');

        $leave = LeaveApplication::with('user.userBasicInfo')->where('id', $id)->first();
        $currentYearLeave = $leave->user->yearlyLeaves()->where('year', $currentYear)->first();;
        $data['pagetitle'] = 'Leaves Management';
        $data['leave'] = $leave;
        $data['leave_id'] = $id;
        $data['currentYearLeave'] = $currentYearLeave;
        return view('hr.user_edit', ['data' => $data]);
    }

    public  function user_upload($id)
    {
        $currentYear = date('Y');

        $leave = LeaveApplication::with('user.userBasicInfo')->where('id', $id)->first();
        $currentYearLeave = $leave->user->yearlyLeaves()->where('year', $currentYear)->first();;
        $data['pagetitle'] = 'Leaves Management';
        $data['leave'] = $leave;
        $data['leave_id'] = $id;
        $data['currentYearLeave'] = $currentYearLeave;
        return view('hr.user_upload', ['data' => $data]);
    }

    public function users_leave_days()
    {
        $currentYear = date('Y');

        $users = User::with(['yearlyLeaves' => function ($query) use ($currentYear) {
            $query->where('year', $currentYear);
        }])
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->where('active_status', 1)
            ->get();


        $data['pagetitle'] = "Users Leave Days";
        $data['users'] =  $users;
        return view('hr.users_leave_days',['data'=>$data]);
    }


    public function assign_leave_days($id)
    {
        $data['pagetitle'] = "User Leave Days";
        $data['user_id'] = $id;
        return view('hr.assign_leave',['data'=>$data]);
    }

    public function assign_user_leave(Request $request,$userId)
    {
        try {
            // Validation rules
            $validatedData = $request->validate([
                'year' => 'required|integer',
                'days' => 'required|integer',

            ]);
            // Check if a yearly leave record already exists for the user and year
            $existingLeave = YearlyLeave::where('user_id', $userId)
                ->where('year', $validatedData["year"])
                ->first();

            if ($existingLeave) {
                // If a record already exists, return a message indicating so
                return redirect()->back()->with('error', 'Leave days for this user and year already exist.');
            }

            // Create a new YearlyLeave instance
            $leaveDays = new YearlyLeave();
            $leaveDays->user_id = $userId;
            $leaveDays->year = $validatedData["year"];
            $leaveDays->days_allocated = $validatedData["days"];
            $leaveDays->save();


            // Return a success response
            return redirect()->route("leaves.users_leave_days")->with('success', 'Leave days has been successfully updated');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to submit Leave days : ' . $e->getMessage());
        }
    }

    public function apply_leave(Request $request)
    {

        try {
            // Validation rules
            $validatedData = $request->validate([
                'leave_type' => 'required|in:annual,medical,compassionate,other',
                'from' => 'required|date',
                'to' => 'required|date|after_or_equal:from',
                'leave_days' => 'required|integer|min:1',
                'reason' => 'required|string',
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            // Create a new Facility instance
            $leaveApplication = new LeaveApplication();
            $leaveApplication->user_id = $user_id;
            $leaveApplication->leave_type = $validatedData["leave_type"];
            $leaveApplication->start_date = $validatedData["from"];
            $leaveApplication->end_date = $validatedData["to"];
            $leaveApplication->days = $validatedData["leave_days"];
            $leaveApplication->reason = $validatedData["reason"];
            $leaveApplication->save();

            // Return a success response
            return redirect()->route('leaves.user_index')->with('success', 'Leave request has been successfully submitted');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to submit Leave request : ' . $e->getMessage());
        }
    }

    public function edit_leave(Request $request, $id)
    {
        try {
            // Validation rules
            $validatedData = $request->validate([
                'leave_type' => 'required|in:annual,medical,compassionate,other',
                'from' => 'required|date',
                'to' => 'required|date|after_or_equal:from',
                'leave_days' => 'required|integer|min:1',
                'reason' => 'required|string',
            ]);

           $leave =  LeaveApplication::findOrFail($id);
            $leave->update($validatedData);

            // Return a success response
            return redirect()->route('leaves.user_index')->with('success', 'Leave request has been successfully Updated');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to update Leave request : ' . $e->getMessage());
        }
    }

    public function approve_leave(Request $request,$id)
    {
        try {
            // Validation rules
            $validatedData = $request->validate([
                'comment' => 'sometimes',
                'status' => 'required|string',
            ]);

            // Retrieve the leave application by its ID
            $leaveApplication = LeaveApplication::where('id', $id)->first();

            // Check if the leave application exists
            if ($leaveApplication) {
                // Update the attributes
                $leaveApplication->comments = $validatedData["comment"];
                $leaveApplication->statuz = $validatedData["status"];
                // Save the changes to the database
                $leaveApplication->save();
            } else {
                return redirect()->back()->with('error', 'Leave Application not found ');
            }

            // Return a success response
            return redirect()->back()->with('success', 'Leave request has been successfully Approved');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to Approve Leave request : ' . $e->getMessage());
        }
    }

    public function show_leave($id)
    {
        $currentYear = date('Y');

        $leave = LeaveApplication::with('user.userBasicInfo')->where('id', $id)->first();
        $currentYearLeave = $leave->user->yearlyLeaves()->where('year', $currentYear)->first();;
        $data['pagetitle'] = 'Leaves Management';
        $data['leave'] = $leave;
        $data['leave_id'] = $id;
        $data['currentYearLeave'] = $currentYearLeave;
        return view('hr.edit_leave', ['data' => $data]);
    }


}
