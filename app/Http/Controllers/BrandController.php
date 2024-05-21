<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $data['pagetitle'] = 'List of Brands';
        $data['brands'] = Brand::all();


        return view('brand.index', ['data' => $data]);
    }

    public function create(Request $request): View
    {
        return view('brand.create');
    }

    public function store(BrandStoreRequest $request): Response
    {
        $brand = Brand::create($request->validated());

        $request->session()->flash('brand.id', $brand->id);

        return redirect()->route('brand.index');
    }

    public function show(Request $request, Brand $brand): Response
    {
        return view('brand.show', compact('brand'));
    }

    public function edit(Request $request, Brand $brand): Response
    {
        return view('brand.edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand): Response
    {
        $brand->update($request->validated());

        $request->session()->flash('brand.id', $brand->id);

        return redirect()->route('brand.index');
    }

    public function destroy(Request $request, Brand $brand): Response
    {
        $brand->delete();

        return redirect()->route('brand.index');
    }
}
