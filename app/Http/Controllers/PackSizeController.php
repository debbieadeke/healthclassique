<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackSizeStoreRequest;
use App\Http\Requests\PackSizeUpdateRequest;
use App\Models\PackSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class PackSizeController extends Controller
{
    public function index(Request $request): Response
    {
        $packSizes = PackSize::all();

        return view('packSize.index', compact('packSizes'));
    }

    public function create(Request $request): Response
    {
        return view('packSize.create');
    }

    public function store(PackSizeStoreRequest $request): Response
    {
        $packSize = PackSize::create($request->validated());

        $request->session()->flash('packSize.id', $packSize->id);

        return redirect()->route('packSize.index');
    }

    public function show(Request $request, PackSize $packSize): Response
    {
        return view('packSize.show', compact('packSize'));
    }

    public function edit(Request $request, PackSize $packSize): Response
    {
        return view('packSize.edit', compact('packSize'));
    }

    public function update(PackSizeUpdateRequest $request, PackSize $packSize): Response
    {
        $packSize->update($request->validated());

        $request->session()->flash('packSize.id', $packSize->id);

        return redirect()->route('packSize.index');
    }

    public function destroy(Request $request, PackSize $packSize): Response
    {
        $packSize->delete();

        return redirect()->route('packSize.index');
    }
}
