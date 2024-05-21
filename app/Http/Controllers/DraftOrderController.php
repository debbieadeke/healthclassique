<?php

namespace App\Http\Controllers;

use App\Models\DraftOrder;
use App\Models\ProductionSetting;
use Illuminate\Http\Request;

class DraftOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pagetitle'] = 'Draft Orders';
        $data['draft_orders'] = DraftOrder::all();

        return view('draft-orders.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data['title'] = "Update Draft Order";
        $data['product_settings'] = ProductionSetting::with('product')->get();

        return view('draft-orders.update', ['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DraftOrder $draftOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DraftOrder $draftOrder)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DraftOrder $draftOrder)
    {
        //
    }
}
