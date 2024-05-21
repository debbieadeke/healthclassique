@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Supplier</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Create
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

                <div class="card card-body">
                    <h5>Create New Supplier</h5>

                    <form action="{{route('suppliers.store')}}" method="post" id="myForm">
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="name">Company Name</label>
                                <input type="text" id="name" name="name"           class="form-control" placeholder="" />
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="mb-3">
                                <label for="phone_number">Phone No</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="" />
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="mb-3">
                                <label for="email_address">Email Address</label>
                                <input type="email" id="email_address" name="email_address" class="form-control" placeholder="" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="contact_person_first_name">Contact Person First Name</label>
                            <input type="text" id="contact_person_first_name" name="contact_person_first_name"           class="form-control" placeholder="" />
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="mb-3">
                            <label for="contact_person_last_name">Contact Person Last Name</label>
                            <input type="text" id="contact_person_last_name" name="contact_person_last_name"           class="form-control" placeholder="" />
                        </div>
                    </div>
                </div>

                    <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="building">Building</label>
                                    <input type="text" id="building" name="building"           class="form-control" placeholder="" />
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="road">Road</label>
                                    <input type="text" id="road" name="road"           class="form-control" placeholder="" />
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="mb-3">
                                    <label for="location">Location / Area</label>
                                    <input type="text" id="location" name="location"           class="form-control" placeholder="" />
                                </div>
                            </div>
                        </div>

                    <div class="row">

                    </div>


                        <div class="mb-3">
                            <button type="submit" class="btn btn-success" name="action" value="suppler_submit">Save Supplier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
@stop
