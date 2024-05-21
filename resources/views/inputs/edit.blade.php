@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Input</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Edit Input
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
                <form action="{{route('input.update', [$input->id])}}" method="post" id="myForm">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-2">
                            <!-- Double Call (Drop-down) -->
                            <div class="mb-3">
                                <label for="product_phase_id" class="form-label">Select Input Type</label>
                                <select class="form-control select2" style="width: 100%; height: 40px" id="type" name="type" required>
                                    @foreach($input_types as $input_type)
                                        @if($input->type == $input_type)
                                            <option value="{{strtolower($input_type)}}" selected>{{$input_type}}</option>
                                        @else
                                            <option value="{{strtolower($input_type)}}">{{$input_type}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$input->name}}">
                        </div>
                        <div class="col-md-2">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{$input->code}}">
                        </div>
                        <div class="col-md-3">
                            <div class="mt-4">

                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Update</button>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">

                        </div>
                    </div>
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            &nbsp;&nbsp;
                        </div>
                        <div class="col-md-3" id="no_of_phases_div">
                            &nbsp;
                        </div>
                        <div class="col-md-3" id="product_category_div">
                            &nbsp;
                        </div>
                        <div class="col-md-2 align-items-end">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
@stop
