<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pagetitle'] = 'List of Inputs';
        $data['inputs'] = Input::all();

        return view('inputs.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $role = $user->roles;

        $data['role'] = $role[0]->name;

        if ($user == null) {
            Auth::logout();
session()->flush();  // Clears all session data
return redirect('/');
        }
        $data['pagetitle'] = 'Create New Input';

        return view('inputs.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = new Input();
        $input->name = $request->name;
        $input->code = $request->code;
        $input->type = $request->type;
        if ($request->type == "packaging") {
            $input->quantity_determinant = "Yes";
        } else {
            $input->quantity_determinant = "No";
        }
        $input->save();

        return redirect()->route('input.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Input $input)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $input = Input::findOrFail($id);
        $data['input_types'] = [
            'Ingredient','Packaging','Miscellaneous'
        ];
        $data['pagetitle'] = 'Edit Input';
        $data['input'] = $input;

        return view('inputs.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Input $input)
    {
        $input->name = $request->name;
        $input->code = $request->code;
        $input->type = $request->type;

        $input->update();

        return redirect()->route('input.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Input $input)
    {
        $input->delete();

		return redirect()->route('input.index')->with('success', 'Input deleted successfully.');
	}
}
