@extends('layouts.app-v2')
@section('content-v2')

    <div class="card card-default">
        <div class="card-header clearfix">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Update Location</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted text-decoration-none" href="{{route('locations.location.index')}}">Locations</a> <i class="fas fa-angle-right"></i>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                Update Location
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                    </div>
                </div>
            </div>
        </div>




{{--        <div class="card-header clearfix">--}}

{{--            <div class="float-start">--}}
{{--                <h4 class="mt-5 mb-5">{{ !empty($location->name) ? $location->name : 'Location' }}</h4>--}}
{{--            </div>--}}
{{--            <div class="btn-group btn-group-sm float-end" role="group">--}}

{{--                <a href="{{ route('locations.location.index') }}" class="btn btn-primary" title="Show All Location">--}}
{{--                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>--}}
{{--                </a>--}}

{{--                <a href="{{ route('locations.location.create') }}" class="btn btn-success" title="Create New Location">--}}
{{--                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>--}}
{{--                </a>--}}

{{--            </div>--}}
{{--        </div>--}}

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body">
                <form method="POST" action="{{ route('locations.location.update', $location->id) }}" id="edit_location_form" name="edit_location_form" accept-charset="UTF-8" class="form-horizontal">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="PUT">
                    @include ('locations.form', ['location' => $location,])
                   <div class="row pt-2">
                       <div class="form-group">
                           <div class="col-md-offset-2 col-md-10">
                               <input class="btn btn-primary" type="submit" value="Update">
                           </div>
                       </div>
                   </div>
                </form>

            </div>
        </div>


    </div>

@endsection
