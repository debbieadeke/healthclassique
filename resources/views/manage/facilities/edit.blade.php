@extends('layouts.app-v2')

@section('content-v2')

    <div class="card card-default">
  
        <div class="card-header clearfix">

            <div class="float-start">
                <h4 class="mt-5 mb-5">{{ !empty($location->name) ? $location->name : 'Location' }}</h4>
            </div>
            <div class="btn-group btn-group-sm float-end" role="group">

                <a href="{{ route('locations.location.index') }}" class="btn btn-primary" title="Show All Location">
                    <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('locations.location.create') }}" class="btn btn-success" title="Create New Location">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('locations.location.update', $location->id) }}" id="edit_location_form" name="edit_location_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('locations.form', [
                                        'location' => $location,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection