<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufacturingController extends Controller
{
    public function production()
    {
        return view('manufacturing.production');
    }
    public function chem_store()
    {
        return view('manufacturing.chem_store');
    }
    public function production_form()
    {
        return view('manufacturing.production_form');
    }
    public function chemstore_form()
    {
        return view('manufacturing.chemstore_form');
    }
    public function manufacturing_records()
    {
        return view('manufacturing.manufacturing_records');
    }
    public function quality_analysis_report(){
        return view('manufacturing.quality_analysis_report');
    }
}
