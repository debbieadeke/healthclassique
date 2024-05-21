<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionBatchStoreRequest;
use App\Http\Requests\ProductionBatchUpdateRequest;
use App\Models\ProductionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class ProductionBatchController extends Controller
{
    public function index(Request $request): Response
    {
        $productionBatches = ProductionSetting::all();

        return view('productionBatch.index', compact('productionBatches'));
    }

    public function create(Request $request): Response
    {
        return view('productionBatch.create');
    }

    public function store(ProductionBatchStoreRequest $request): Response
    {
        $productionBatch = ProductionSetting::create($request->validated());

        $request->session()->flash('productionBatch.id', $productionBatch->id);

        return redirect()->route('productionBatch.index');
    }

    public function show(Request $request, ProductionSetting $productionBatch): Response
    {
        return view('productionBatch.show', compact('productionBatch'));
    }

    public function edit(Request $request, ProductionSetting $productionBatch): Response
    {
        return view('productionBatch.edit', compact('productionBatch'));
    }

    public function update(ProductionBatchUpdateRequest $request, ProductionSetting $productionBatch): Response
    {
        $productionBatch->update($request->validated());

        $request->session()->flash('productionBatch.id', $productionBatch->id);

        return redirect()->route('productionBatch.index');
    }

    public function destroy(Request $request, ProductionSetting $productionBatch): Response
    {
        $productionBatch->delete();

        return redirect()->route('productionBatch.index');
    }
}
