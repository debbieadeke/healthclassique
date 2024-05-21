@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Product</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Edit Product
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
                <form action="{{route('products.update', [$product->id])}}" method="post" id="myForm">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$product->name}}">
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label">Product Code</label>
                            <input type="text" class="form-control" id="name" name="code" value="{{$product->code}}">
                        </div>
                        <div class="col-md-4">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price" value="{{$product->price}}" step="0.01">
                        </div>
                    </div>
                    <div class="row mt-8">
                        <div class="col-md-4">
                            <label for="team" class="form-label">Team</label>
                            <select class="form-control" id="team" name="team">
                                <option value="" disabled {{ !$product->team_id ? 'selected' : '' }}>Select a Team</option>
                                @foreach($data['teams'] as $team)
                                    <option value="{{ $team->id }}" {{ $product->team_id == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-8" style="padding-top: 40px">
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
