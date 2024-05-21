@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">My Target</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('targets.customers')}}">Select a Clinic</a> <i class="fas fa-angle-right"></i></li>
                                <li class="breadcrumb-item"><a href="">Clinics Sales Target</a> <i class="fas fa-angle-right"></i></li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <a class="text-muted text-decoration-none" href="{{route('targets.index')}}">Set Target</a>
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
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <form action="{{route('targets.store_clinic',['id' => $product->first()->id, 'code' => $code]) }}" method="post" id="myForm">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" placeholder="product Name" name="name" value="{{ $product->first()->name }}">
                            <input type="hidden" name="customer_code" value="{{ $code }}">
                        </div>
                        <div class="col-md-4">
                            <label for="quart" class="form-label">Quarter</label>
                            <select class="form-select" id="quart" name="quarter">
                                <option value="" disabled selected>Select Quarter</option>
                                <option value="1">1st  Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4rd Quarter</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 30px" id="quarter-1">
                        <div class="col-md-4">
                            <label for="january" class="form-label">January Target</label>
                            <input type="number" class="form-control" id="january" name="january"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="february" class="form-label">February Target</label>
                            <input type="number" class="form-control" id="february" name="february"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="march" class="form-label">March Target</label>
                            <input type="number" class="form-control" id="march" name="march"  autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 30px" id="quarter-2">
                        <div class="col-md-4">
                            <label for="april" class="form-label">April Target</label>
                            <input type="number" class="form-control" id="april" name="april"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="may" class="form-label">May Target</label>
                            <input type="number" class="form-control" id="may" name="may"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="june" class="form-label">June Target</label>
                            <input type="number" class="form-control" id="june" name="june"  autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 30px" id="quarter-3">
                        <div class="col-md-4">
                            <label for="july" class="form-label">July Target</label>
                            <input type="number" class="form-control" id="july" name="july"  autocomplete="off" value="" >
                        </div>
                        <div class="col-md-4">
                            <label for="august" class="form-label">August Target</label>
                            <input type="number" class="form-control" id="august" name="august"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="september" class="form-label">September Target</label>
                            <input type="number" class="form-control" id="september" name="september"  autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 30px" id="quarter-4">
                        <div class="col-md-4">
                            <label for="october" class="form-label">October Target</label>
                            <input type="number" class="form-control" id="october" name="october"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="november" class="form-label">November Target</label>
                            <input type="number" class="form-control" id="november" name="november"  autocomplete="off" value="">
                        </div>
                        <div class="col-md-4">
                            <label for="december" class="form-label">December Target</label>
                            <input type="number" class="form-control" id="december" name="december"  autocomplete="off" value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="row" style="padding-top: 20px;">
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success" name="action" value="item_submit">Save</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Hide all quarter rows initially
            $('[id^="quarter"]').hide();

            // Show/hide quarter rows based on the selected quarter
            $('#quart').change(function () {
                var selectedQuarter = $(this).val();
                $('[id^="quarter-"]').hide();
                $('#quarter-' + selectedQuarter).show();

                // Set the required attribute for the monthly targets based on the selected quarter
                var requiredMonths = [];
                switch (selectedQuarter) {
                    case '1':
                        requiredMonths = ['january', 'february', 'march'];
                        break;
                    case '2':
                        requiredMonths = ['april', 'may', 'june'];
                        break;
                    case '3':
                        requiredMonths = ['july', 'august', 'september'];
                        break;
                    case '4':
                        requiredMonths = ['october', 'november', 'december'];
                        break;
                }

                $('.monthly-target').prop('required', false); // Remove required from all
                requiredMonths.forEach(function (month) {
                    $('#' + month).prop('required', true); // Add required for selected months
                });
            });
        });
    </script>
@endsection


