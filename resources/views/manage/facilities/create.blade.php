@extends('layouts.app-v2')

@section('content-v2')

    <div class="card card-default">

        <div class="card-header clearfix">

            <span class="float-start">
                <h4 class="mt-5 mb-5">Create New Facility</h4>
            </span>

			<a class="btn btn-primary float-end" href="{{ route('locations.location.index') }}" role="button">Show Locations</a>

        </div>

        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('locations.location.store') }}" accept-charset="UTF-8" id="create_location_form" name="create_location_form" class="form-horizontal">
            {{ csrf_field() }}


                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Add">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection


