@extends('layouts.app-v2')

@section('content-v2')

    <div class="card card-default p-2">

        <div class="card-header clearfix">

            <div class="float-start">
                <h4 class="mt-5 mb-5">Locations</h4>
            </div>
			<a class="btn btn-primary float-end" href="{{ route('locations.location.create') }}" role="button">Create New Location</a>
        </div>

        @if(count($locations) == 0)
            <div class="card-body text-center">
                <h4>No Locations Available.</h4>
            </div>
        @else
        <div class="card-body">
            <div class="p-2">
                <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                    <thead>
                        <tr style="font-size:14px">
                            <th style="width: 5px">No</th>
                            <th>Name</th>
                            <th>Territory</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr style="font-size: 13px">
                            <td>{{$loop->iteration}}</td>
                            <td>{{ $location->name }}</td>
                            <td>{{ optional($location->Territory)->name }}</td>
                            <td>
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('locations.location.create') }}"><i class="fas fa-square-plus" style="color:green; font-size: 18px;"></i> Create Location</a>
                                        <a class="dropdown-item" href="{{route('locations.location.edit',['location' => $location->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Location</a>
                                        <span class="dropdown-item">
                                        <form action="{{ route('client-users.destroy-clients', ['id' => $location->id]) }}" method="POST" id="deleteForm{{$location->id}}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $location->id }}"/>
                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Client?');" style="padding: 0;">
                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Location
                                            </button>
                                        </form>
                                    </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
@endsection
