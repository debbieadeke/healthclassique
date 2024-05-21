@extends('layouts.app-v2')

@section('content-v2')

<div class="card card-default">
    <div class="card-header clearfix">

        <span class="float-start">
            <h4 class="mt-5 mb-5">{{ isset($location->name) ? $location->name : 'Location' }}</h4>
        </span>

        <div class="float-end">

            <form method="POST" action="{!! route('locations.location.destroy', $location->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('locations.location.index') }}" class="btn btn-primary" title="Show All Location">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('locations.location.create') }}" class="btn btn-success" title="Create New Location">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <a href="{{ route('locations.location.edit', $location->id ) }}" class="btn btn-primary" title="Edit Location">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>
                    <button type="submit" class="btn btn-danger" title="Delete Location" onclick="return confirm(&quot;Click Ok to delete Location.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="card-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $location->name }}</dd>
            <dt>Longitude</dt>
            <dd>{{ $location->longitude }}</dd>
            <dt>Latitude</dt>
            <dd>{{ $location->latitude }}</dd>
            <dt>Territory</dt>
            <dd>{{ optional($location->Territory)->name }}</dd>
            <dt>Created By</dt>
            <dd>{{ optional($location->creator)->name }}</dd>
            <dt>Created At</dt>
            <dd>{{ $location->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $location->updated_at }}</dd>
            <dt>Deleted At</dt>
            <dd>{{ $location->deleted_at }}</dd>

        </dl>

    </div>
</div>

@endsection
