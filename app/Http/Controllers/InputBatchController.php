<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputBatchStoreRequest;
use App\Http\Requests\InputBatchUpdateRequest;
use App\Models\Input;
use App\Models\InputBatch;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InputBatchController extends Controller
{
    public function index()
    {
        $data['pagetitle'] = 'List of Input Batches';
        $data['input_batches'] = InputBatch::with(['input','supplier'])
            ->where('quantity_remaining', '>', 0)
            ->orderBy('quantity_remaining', 'desc')
            ->get();


        return view('inputbatch.index', ['data' => $data]);
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
        $data['pagetitle'] = 'Receive New Input Batch';
        $data['inputs'] = Input::all();
        $data['suppliers'] = Supplier::all();

        return view('inputbatch.create', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $inputbatch = new InputBatch();
        $inputbatch->input_id = $request->input_id;
        $inputbatch->supplier_id = $request->supplier_id;
        $inputbatch->batch_number = $request->batch_number;
        $inputbatch->lpo = $request->lpo;
        $inputbatch->buying_price = $request->buying_price;
        $inputbatch->selling_price = 0;

        $inputbatch->date_supplied = now();

        $qty_purchased = str_replace( ',', '', $request->quantity_purchased );

        $inputbatch->quantity_purchased = $qty_purchased;
        $inputbatch->quantity_remaining = $qty_purchased;
        $inputbatch->manufacture_date = $request->manufacture_date;
        $inputbatch->expiry_date = $request->expiry_date;

        $inputbatch->save();

        return redirect()->route('input-batch.index');
    }

    public function show(Request $request, InputBatch $InputBatch): Response
    {
        return view('InputBatch.show', compact('InputBatch'));
    }

    public function edit(Request $request, $id)
    {
        $inputbatch = InputBatch::findOrFail($id);
        $data['inputs'] = Input::all();
        $data['pagetitle'] = 'Edit Input Batch';
        $data['inputbatch'] = $inputbatch;
        $data['suppliers'] = Supplier::all();

        return view('inputbatch.edit', ['data' => $data]);
    }

    public function update(Request $request, InputBatch $inputbatch)
    {

        $inputbatch->input_id = $request->input_id;
        $inputbatch->supplier_id = $request->supplier_id;
        $inputbatch->batch_number = $request->batch_number;
        $inputbatch->lpo = $request->lpo;
        $inputbatch->buying_price = $request->buying_price;
        $inputbatch->selling_price = 0;

        $inputbatch->date_supplied = now();

        $qty_purchased = str_replace( ',', '', $request->quantity_purchased );

        $inputbatch->quantity_purchased = $qty_purchased;
        $inputbatch->quantity_remaining = $qty_purchased;
        $inputbatch->manufacture_date = $request->manufacture_date;
        $inputbatch->expiry_date = $request->expiry_date;

        $inputbatch->update();

        return redirect()->route('input-batch.index');
    }

    public function destroy(Request $request, InputBatch $inputBatch)
    {
        $inputBatch->delete();

        return redirect()->route('input-batch.index')->with('success', 'Input Batch deleted successfully.');

    }

    public function report_stock()
    {
        $data['pagetitle'] = 'Stock Report';
        $data['input_batches'] = InputBatch::with(['input','supplier'])
            ->where('quantity_remaining', '>', 0)
            ->orderBy('quantity_remaining', 'desc')
            ->get();

        return view('inputbatch.stock-report', ['data' => $data]);
    }
}
