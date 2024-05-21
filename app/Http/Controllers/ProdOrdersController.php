<?php

namespace App\Http\Controllers;

use App\Models\ProdOrder;
use Illuminate\Http\Request;

class ProdOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        ProdOrder::create([
            'product_id' =>,
            'product_quantity_target' =>,
            'input_id' =>,
            'percentage' =>,
            'grams_to_be_added' =>,
            'ingredient_cost' =>,
            'ingredient_cost_formula' =>,
            'phaseids' =>,
            'packaging_id' => ,
            'packsize'=>,
            'batchqty'=,
        ])
        
        info($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
