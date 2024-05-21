<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class LocationsController extends Controller
{

    /**
     * Display a listing of the locations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //$locations = Location::with('territory','creator')->paginate(25);
        $locations = Location::with('territory')->orderBy('name')->get();

        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $Territories = Territory::pluck('name','id')->all();
        $creators = User::pluck('first_name','id')->all();


        return view('locations.create', compact('Territories','creators'));
    }

    /**
     * Store a new location in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {

            //$data = $this->getData($request);
            //dd($data);

            $location = new Location();
            $location->name = $request->get('name');
            $location->territory_id = $request->get('territory_id');
            $location->longitude = null;
            $location->latitude = null;
            $location->created_by = Auth::Id();
            $location->save();

            toastr()->success('Location created successfully');

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully added.');
        } catch (Exception $exception) {
            //dd($exception);
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Display the specified location.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $location = Location::with('territory','creator')->findOrFail($id);

        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $Territories = Territory::pluck('name','id')->all();
        $creators = User::pluck('name','id')->all();

        return view('locations.edit', compact('location','Territories','creators'));
    }

    /**
     * Update the specified location in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        try {

            $data = $this->getData($request);

            $location = Location::findOrFail($id);
            $location->update($data);

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully updated.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }

    /**
     * Remove the specified location from the storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse | \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();

            return redirect()->route('locations.location.index')
                ->with('success_message', 'Location was successfully deleted.');
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
                'name' => 'required|string|min:1|max:255',
            'longitude' => 'nullable|numeric|min:-999.9999999|max:999.9999999',
            'latitude' => 'nullable|numeric|min:-999.9999999|max:999.9999999',
            'territory_id' => 'required',
            'created_by' => 'nullable',
        ];


        $data = $request->validate($rules);




        return $data;
    }

}
