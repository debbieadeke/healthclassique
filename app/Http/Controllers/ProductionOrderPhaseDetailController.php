<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchPhaseDetailStoreRequest;
use App\Http\Requests\ProductionBatchPhaseDetailUpdateRequest;
use App\Models\ProductionOrderPhaseDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class ProductionOrderPhaseDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $productionBatchPhaseDetails = ProductionOrderPhaseDetail::all();

        return view('productionBatchPhaseDetail.index', compact('productionBatchPhaseDetails'));
    }

    public function create(Request $request): Response
    {
        return view('productionBatchPhaseDetail.create');
    }

    public function store(ProductionBatchPhaseDetailStoreRequest $request): Response
    {
        $productionBatchPhaseDetail = ProductionOrderPhaseDetail::create($request->validated());

        $request->session()->flash('productionBatchPhaseDetail.id', $productionBatchPhaseDetail->id);

        return redirect()->route('productionBatchPhaseDetail.index');
    }

    public function show(Request $request, ProductionOrderPhaseDetail $productionBatchPhaseDetail): Response
    {
        return view('productionBatchPhaseDetail.show', compact('productionBatchPhaseDetail'));
    }

    public function edit(Request $request, ProductionOrderPhaseDetail $productionBatchPhaseDetail): Response
    {
        return view('productionBatchPhaseDetail.edit', compact('productionBatchPhaseDetail'));
    }

    public function update(ProductionBatchPhaseDetailUpdateRequest $request, ProductionOrderPhaseDetail $productionBatchPhaseDetail): Response
    {
        $productionBatchPhaseDetail->update($request->validated());

        $request->session()->flash('productionBatchPhaseDetail.id', $productionBatchPhaseDetail->id);

        return redirect()->route('productionBatchPhaseDetail.index');
    }

    public function destroy(Request $request, ProductionOrderPhaseDetail $productionBatchPhaseDetail): Response
    {
        $productionBatchPhaseDetail->delete();

        return redirect()->route('productionBatchPhaseDetail.index');
    }
}
