@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">My Pharmacy Target</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" href="{{route('targets.pharmacy')}}">Pharmacy Sales Target</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" >Edit Target</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{asset('assets/images/breadcrumb/ChatBc.png')}}" alt="" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{ route('targets.update_pharmacy_targets',['code'=>$code]) }}" method="post" id="myForm">
                    @csrf
                    <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                        <div class="col-md-4">
                            <label for="product" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product" placeholder="product Name" name="product" value="{{ $product }}" readonly>
                        </div>
                    </div>
                    @foreach($targetsByQuarter as $quarter => $quarterTargets)
                        <div class="quarter-section" id="quarter-{{ $quarter }}">
                            <label for="quarter" class="form-label"><b>Quarter {{ $quarter }}</b></label>
                            <div class="row" style="padding-top: 30px; padding-bottom: 30px">
                                @foreach($quarterTargets as $target)
                                    <div class="col-md-4">
                                        <label for="{{ $target['month'] }}" class="form-label">{{ ucfirst($target['month']) }}</label>
                                        <input type="number" class="form-control" id="{{ $target['month'] }}" name="{{ $target['month'] }}" autocomplete="off" value="{{ $target['target'] }}">
                                        <input type="hidden" name="target_ids[]" value="{{ $target['id'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <div class="row" style="padding-top: 20px;">
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Update</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection



