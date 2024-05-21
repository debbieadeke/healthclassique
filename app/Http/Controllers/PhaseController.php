<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhaseStoreRequest;
use App\Http\Requests\PhaseUpdateRequest;
use App\Models\Phase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;

class PhaseController extends Controller
{
    public function index(Request $request): Response
    {
        $phases = Phase::all();

        return view('phase.index', compact('phases'));
    }

    public function create(Request $request): Response
    {
        return view('phase.create');
    }

    public function store(PhaseStoreRequest $request): Response
    {
        $phase = Phase::create($request->validated());

        $request->session()->flash('phase.id', $phase->id);

        return redirect()->route('phase.index');
    }

    public function show(Request $request, Phase $phase): Response
    {
        return view('phase.show', compact('phase'));
    }

    public function edit(Request $request, Phase $phase): Response
    {
        return view('phase.edit', compact('phase'));
    }

    public function update(PhaseUpdateRequest $request, Phase $phase): Response
    {
        $phase->update($request->validated());

        $request->session()->flash('phase.id', $phase->id);

        return redirect()->route('phase.index');
    }

    public function destroy(Request $request, Phase $phase): Response
    {
        $phase->delete();

        return redirect()->route('phase.index');
    }
}
