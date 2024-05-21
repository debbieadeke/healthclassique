<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitOfMeasureStoreRequest;
use App\Http\Requests\UnitOfMeasureUpdateRequest;
use App\Models\UnitOfMeasure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitOfMeasureController extends Controller
{
    public function index(Request $request): Response
    {
        $unitOfMeasures = UnitOfMeasure::all();

        return view('unitOfMeasure.index', compact('unitOfMeasures'));
    }

    public function create(Request $request): Response
    {
        return view('unitOfMeasure.create');
    }

    public function store(UnitOfMeasureStoreRequest $request): Response
    {
        $unitOfMeasure = UnitOfMeasure::create($request->validated());

        $request->session()->flash('unitOfMeasure.id', $unitOfMeasure->id);

        return redirect()->route('unitOfMeasure.index');
    }

    public function show(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        return view('unitOfMeasure.show', compact('unitOfMeasure'));
    }

    public function edit(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        return view('unitOfMeasure.edit', compact('unitOfMeasure'));
    }

    public function update(UnitOfMeasureUpdateRequest $request, UnitOfMeasure $unitOfMeasure): Response
    {
        $unitOfMeasure->update($request->validated());

        $request->session()->flash('unitOfMeasure.id', $unitOfMeasure->id);

        return redirect()->route('unitOfMeasure.index');
    }

    public function destroy(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        $unitOfMeasure->delete();

        return redirect()->route('unitOfMeasure.index');
    }
}
