<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PrivacyDocument;
use App\Models\Team;
use App\Models\User;
use App\Models\UserBasicInfo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRoles = $user->getRoleNames();
        $users = User::with('team')->paginate(10);
        return view('user.index', ['users' => $users, 'userRoles' => $userRoles]);
    }


    public function create()
    {
        $data['pagetitle'] = 'Create New User';
        $teams = Team::all();
        $roles = Role::all();
        $data['teams'] = $teams;
        $data['roles'] = $roles;

        return view('user.create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'role' => 'required|string',
                'status' => 'required|in:0,1',
            ]);

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->team_id = $request->team;
            $user->active_status = $request->status;
            $user->save();

            $user->assignRole($request->role);

            return redirect()->route('users.index')->with('success', 'User created successfully');

        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return redirect()->back()->withInput()->with('error', 'Database error occurred.');
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        // Your code for displaying a specific user goes here
        return view('users.show', ['user' => User::find($id)]); // Adjust the view name as needed
    }

    public function edit($id)
    {

        $data['pagetitle'] = 'Edit User';
        $user = User::find($id);

        $userRoles = $user->roles->pluck('name')->toArray();

        $data['user'] = $user;
        $data['teams'] = Team::all();
        $data['roles'] = Role::all();

        return view('user.edit', ['data' => $data, 'userRoles' => $userRoles]);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);


            $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role' => 'required|string',
                'status' => 'required|in:0,1',
                'password' => $request->input('change_password') ? 'required|min:8' : '',
            ]);

            $user = User::findOrFail($id);
            $user->syncRoles([$request->input('role')]);

            $user->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'team_id' => $request->input('team'),
                'active_status' => $request->input('status'),
                'password' => $request->input('change_password') ? bcrypt($request->input('password')) : $user->password,
            ]);

            return redirect()->route('users.index')->with('success', 'User updated successfully');

        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error occurred.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User has been successfully deleted');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    //  Profile pages
    public function myProfile()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $user = User::where('id', $user_id)->first();
        $basic = UserBasicInfo::with('user')->where('user_id', $user_id)->first();
        $educations = Education::where('user_id', $user_id)->get();
        $experiences = Experience::where('user_id', $user_id)->get();

        $data['pagetitle'] = 'My Profile';
        $data['user'] = $user;
        $data['basic'] = $basic;
        $data['educations'] = $educations;
        $data['experiences'] = $experiences;
        return view('user.profile', ['data' => $data]);
    }

    public function edit_profile()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
            session()->flush();  // Clears all session data
            return redirect('/');
        }
        $user_id = $user->id;
        $user = User::where('id', $user_id)->first();
        $basic = UserBasicInfo::with('user')->where('user_id', $user_id)->first();
        $educations = Education::where('user_id', $user_id)->get();
        $experiences = Experience::where('user_id', $user_id)->get();

        $data['pagetitle'] = 'My Profile';
        $data['user'] = $user;
        $data['basic'] = $basic;
        $data['educations'] = $educations;
        $data['experiences'] = $experiences;
        return view('user.edit_profile', ['data' => $data]);
    }

    public function viewPdf($filename)
    {
        $filePath = storage_path('app/pdfs/' . $filename);

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        return new BinaryFileResponse($filePath);
    }

    // view for privacy policy
    public function privacy_policy()
    {
        $uploads = PrivacyDocument::all();
        $data['pagetitle'] = 'Upload privacy documents';
        $data['uploads'] = $uploads;
        return view('user.privacy_policy', ['data' => $data]);
    }

    public function user_privacy_policy()
    {
        $uploads = PrivacyDocument::all();
        $data['pagetitle'] = 'Privacy Upload';
        $data['uploads'] = $uploads;
        return view('user.user_privacy_policy', ['data' => $data]);
    }

    public function privacy_upload(Request $request)
    {
        try {
            $request->validate([
                'document_name' => 'required|string',
                'pdf' => 'required', // max 2MB
            ]);

            $documentName = $request->document_name;
            $pdfFile = $request->file('pdf');

            // Save the PDF file to the filesystem
            $pdfFile->move(public_path('pdfs'), $documentName);
            $filePath = 'pdfs/' . $documentName;

            // Create a new Pdf model instance
            $pdf = new PrivacyDocument();
            $pdf->document_name = $documentName;
            $pdf->file_path = $filePath;
            $pdf->save();

            return redirect()->route('users.privacy_policy')->with('success', 'PDF has been uploaded successfully deleted');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Uploading files ' . $e->getMessage());
        }

    }

    public function destroy_privacy_upload($id)
    {
        try {
            $user = PrivacyDocument::findOrFail($id);
            $user->delete();

            return redirect()->route('users.privacy_policy')->with('success', 'User has been successfully deleted');
        } catch (\Exception $e) {
            return redirect()->route('users.privacy_policy')->with('error', 'Error deleting Document: ' . $e->getMessage());
        }
    }

    public function basic_info(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string',
                'birthday' => 'required|string',
                'gender' => 'required|string',
                'address' => 'required|string',
                'county' => 'required|string',
                'town' => 'required|string',
                'phone' => 'required|string',
                'date_joined' => 'required|string',
                'employee_id_number' => 'required|string',
                'national_id' => 'required|string',
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;

            $profile = UserBasicInfo::where('user_id', $user_id)->first();

            if (!$profile) {


                $profile = new UserBasicInfo();
                $profile->user_id = $user_id;
                $profile->birthday = $request->input('birthday');
                $profile->gender = $request->input('gender');
                $profile->address = $request->input('address');
                $profile->county = $request->input('county');
                $profile->town = $request->input('town');
                $profile->phone = $request->input('phone');
                $profile->employee_id = $request->input('employee_id_number');
                $profile->national_id = $request->input('national_id');
                $profile->date_joined = $request->input('date_joined');
                if ($request->hasFile('profile_image')) {
                    $profile_img = $request->File('profile_image');
                    $randomNumbers = Str::random(10); // Generate a random string of 10 characters
                    $imageName = $request->first_name . '_' . $randomNumbers . '.' . $profile_img->getClientOriginalExtension();
                    // Save the PDF file to the filesystem
                    $profile_img->move(public_path('img/profiles/'), $imageName);

                    $filePath = 'img/profiles' . $imageName;
                    $profile->image = $filePath;
                }
                $profile->save();

            }

            if ($request->hasFile('profile_image')) {
                $profile_img = $request->file('profile_image');
                $imageName = $request->first_name . '_' . Str::random(10) . '.' . $profile_img->getClientOriginalExtension();
                $profile_img->move(public_path('img/profiles/'), $imageName);
                $filePath = 'img/profiles/' . $imageName;
                $profile->image = $filePath;
            }

            $profile->user_id = $user_id;
            $profile->birthday = $request->input('birthday');
            $profile->gender = $request->input('gender');
            $profile->address = $request->input('address');
            $profile->county = $request->input('county');
            $profile->town = $request->input('town');
            $profile->phone = $request->input('phone');
            $profile->employee_id = $request->input('employee_id_number');
            $profile->national_id = $request->input('national_id');
            $profile->date_joined = $request->input('date_joined');
            $profile->save();

            return redirect()->route('users.edit_profile')->with('success', 'Profile has been successfully Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Updating Profile: ' . $e->getMessage());
        }

    }

    public function education_info(Request $request)
    {
        try {
            $request->validate([
                'institution' => 'required|array',
                'institution.*' => 'required|string',
                'subject' => 'required|array',
                'subject.*' => 'required|string',
                'starting_date' => 'required|array',
                'starting_date.*' => 'required|string',
                'complete_date' => 'required|array',
                'complete_date.*' => 'required|string',
                'degree' => 'required|array',
                'degree.*' => 'required|string',
                'grade' => 'required|array',
                'grade.*' => 'required|string',
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;
            // Delete existing entries for this user
            Education::where('user_id', $user_id)->delete();

            // Loop through each entry and save
            foreach ($request->institution as $key => $value) {
                $education = new Education();
                $education->user_id = $user_id;
                $education->institution = $request->institution[$key];
                $education->subject = $request->subject[$key];
                $education->starting_date = $request->starting_date[$key];
                $education->completion_date = $request->complete_date[$key];
                $education->degree = $request->degree[$key];
                $education->grade = $request->grade[$key];
                $education->save();
            }


            return redirect()->route('users.edit_profile')->with('success', 'Education details has been successfully Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Updating Education details: ' . $e->getMessage());
        }

    }


    public function experience_info(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|array',
                'company_name.*' => 'required|string',
                'job_position' => 'required|array',
                'job_position.*' => 'required|string',
                'location' => 'required|array',
                'location.*' => 'required|string',
                'period_from' => 'required|array',
                'period_from.*' => 'required|string',
                'period_to' => 'required|array',
                'period_to.*' => 'required|string',
            ]);
            $user = Auth::user();
            if ($user == null) {
                Auth::logout();
                session()->flush();  // Clears all session data
                return redirect('/');
            }
            $user_id = $user->id;


            Experience::where('user_id', $user_id)->delete();

            // Loop through each entry and save
            foreach ($request->company_name as $key => $value) {
                $experience = new Experience();
                $experience->user_id = $user_id;
                $experience->company_name = $request->company_name[$key];
                $experience->job_position = $request->job_position[$key];
                $experience->location = $request->location[$key];
                $experience->period_from = $request->period_from[$key];
                $experience->period_to = $request->period_to[$key];
                $experience->save();
            }

            return redirect()->route('users.edit_profile')->with('success', 'Experience details has been successfully Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error Updating Experience details: ' . $e->getMessage());
        }

    }



}
