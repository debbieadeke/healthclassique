@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>Edit Epimol Incetives Metrics</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Epimol Incetives Metrics</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form id="epimolForm" method="post" action="{{ route('incentive.update-epimol-metrics',['id'=> $epimolMetric->id]) }}" enctype="multipart/form-data"  required>
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="percent" class="form-label"><b>Percentage</b></label>
                                        <input type="number" class="form-control" id="percent" name="percent" placeholder="%" value="{{ $epimolMetric->percentage }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="team" class="form-label"><b>Team Value</b></label>
                                        <input type="number" class="form-control" id="team" name="team"  value="{{ $epimolMetric->team }}">
                                    </div> <div class="col-md-3">
                                        <label for="individual" class="form-label"><b>Individual Value</b></label>
                                        <input type="number" class="form-control" id="individual" name="individual"  value="{{ $epimolMetric->individual }}">
                                    </div> <div class="col-md-3">
                                        <label for="kpis" class="form-label"><b>KPI's Value</b></label>
                                        <input type="number" class="form-control" id="kpis" name="kpis"  value="{{ $epimolMetric->kPIs }}">
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-3">
                                        <label for="tier1" class="form-label"><b>EP-Tier 1 value</b></label>
                                        <input type="number" class="form-control" id="tier1" name="tier1"  value="{{ $epimolMetric->ep_tier_1_performance }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tier2" class="form-label"><b>EP-Tier 2 value</b></label>
                                        <input type="number" class="form-control" id="tier2" name="tier2"  value="{{ $epimolMetric->ep_tier_2_performance }}">
                                    </div> <div class="col-md-3">
                                        <label for="tier3" class="form-label"><b>EP-Tier 3 value</b></label>
                                        <input type="number" class="form-control" id="tier3" name="tier3"  value="{{ $epimolMetric->ep_tier_3_performance }}">
                                    </div> <div class="col-md-3">
                                        <label for="total_individual" class="form-label"><b>Total Individual Value</b></label>
                                        <input type="number" class="form-control" id="total_individual" name="total_individual"  value="{{ $epimolMetric->total_individual }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-4">
                                            <button id="submitGpsButton" type="submit" class="btn btn-success" name="action" value="item_submit">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
