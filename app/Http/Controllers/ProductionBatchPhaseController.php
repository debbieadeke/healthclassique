<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchPhaseStoreRequest;
use App\Http\Requests\ProductionBatchPhaseUpdateRequest;
use App\Models\ProductionOrderPhase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class ProductionBatchPhaseController extends Controller
{
    public function index(Request $request): Response
    {
        $productionBatchPhases = ProductionOrderPhase::all();

        return view('productionBatchPhase.index', compact('productionBatchPhases'));
    }

    public function create(Request $request): Response
    {
        return view('productionBatchPhase.create');
    }

    public function store(ProductionBatchPhaseStoreRequest $request): Response
    {
        $productionBatchPhase = ProductionOrderPhase::create($request->validated());

        $request->session()->flash('productionBatchPhase.id', $productionBatchPhase->id);

        return redirect()->route('productionBatchPhase.index');
    }

    public function show(Request $request, ProductionOrderPhase $productionBatchPhase): Response
    {
        return view('productionBatchPhase.show', compact('productionBatchPhase'));
    }

    public function edit(Request $request, ProductionOrderPhase $productionBatchPhase): Response
    {
        return view('productionBatchPhase.edit', compact('productionBatchPhase'));
    }

    public function update(ProductionBatchPhaseUpdateRequest $request, ProductionOrderPhase $productionBatchPhase): Response
    {
        $productionBatchPhase->update($request->validated());

        $request->session()->flash('productionBatchPhase.id', $productionBatchPhase->id);

        return redirect()->route('productionBatchPhase.index');
    }

    public function destroy(Request $request, ProductionOrderPhase $productionBatchPhase): Response
    {
        $productionBatchPhase->delete();

        return redirect()->route('productionBatchPhase.index');
    }
}
