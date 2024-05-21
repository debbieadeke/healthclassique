<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    public function index()
    {
        $data['pagetitle'] = 'List of Suppliers';
        $data['suppliers'] = Supplier::all();

        return view('suppliers.index', ['data' => $data]);
    }

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
        $data['pagetitle'] = 'Create New Supplier';

        return view('suppliers.create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->contact_person_first_name = $request->contact_person_first_name;
        $supplier->contact_person_last_name = $request->contact_person_last_name;
        $supplier->phone_number = $request->phone_number;
        $supplier->email_address = $request->email_address;
        $supplier->building = $request->building;
        $supplier->road = $request->road;
        $supplier->location = $request->location;
        $supplier->save();

        return redirect()->route('suppliers.index');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        $data['pagetitle'] = 'Edit Supplier';
        $data['supplier'] = $supplier;

        return view('suppliers.show', ['data' => $data]);
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        $data['pagetitle'] = 'Edit Supplier';
        $data['supplier'] = $supplier;

        return view('suppliers.edit', ['data' => $data]);
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        $supplier->name = $request->name;
        $supplier->phone_number = $request->phone_number;
        $supplier->email_address = $request->email_address;
        $supplier->contact_person_first_name = $request->contact_person_first_name;
        $supplier->contact_person_last_name = $request->contact_person_last_name;
        $supplier->building = $request->building;
        $supplier->road = $request->road;
        $supplier->location = $request->location;

        $supplier->update();

        return redirect()->route('suppliers.index');
    }

    public function destroy(Request $request, Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
