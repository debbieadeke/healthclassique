@php(extract($data))
@extends('layouts.app-v2')

@section('content-v2')
    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success_message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-default">

        <div class="card-header clearfix">

            <div class="float-start">
                <h4 class="mt-5 mb-5">All {{$pagecategory}}
                    @role('user')
                    | <a href="{{ route('personal-pharmacy-users.index', ['facility_type' => $facility_type]) }}">Manage My {{$pagecategory}} List</a>
                    @endrole
                </h4>
            </div>
            <a href="{{route('pharmacy-users.create')}}" class="btn btn-success float-end" role="button" aria-disabled="true">
                <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                Create
            </a>


			<a class="btn btn-primary float-end" href="{{ route('pharmacy-users.create') }}" role="button" style="visibility: hidden">Create New {{$facility_type}}</a>


        </div>

        @if(count($allclients) == 0)
            <div class="card-body text-center">
                <h4>No {{$pagecategory}} Available.</h4>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            @if(count($allclients) >= 1)
                <div class="container">
                    <div class="row">
                        <div class="p-4 text-end">
                            <button id="exportButton" class="btn btn-primary btn-sm">Export to Excel</button>
                        </div>
                        <form method="post" action="{{ route('pharmacy-users.update') }}"  id="clientForm">
                            @csrf
                            <div class="col-12">
                                <table class="table table-striped" style="width:100%">
                                    <thead>
                                    <tr style="font-size: 14px">
                                        <th></th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>View More</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allclients as $client)
                                        <tr style="font-size: 13px">
                                            <td>
                                                @role('user')
                                                <input class="form-check-input primary" type="checkbox" value="{{$client->id}}" name="client[]" @if ($userclients->contains('id', $client->id))
                                                    checked
                                                    @endif>

                                                @endrole
                                                @role('super_admin')
                                                {{$loop->iteration}}
                                                @endrole
                                            </td>
                                            <td>{{ $client->code }}</td>
                                            <td>{{ $client->name }}</td>
                                            <td>{{ $client->facility_type }}</td>
                                            <td>{{ $client->location->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @notrole('user')
                                                        <a class="dropdown-item" href="{{route('pharmacy.edit',['id' => $client->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Pharmacy</a>
                                                        @endnotrole
                                                        @role('super_admin')
                                                        <span class="dropdown-item">
                                                       <form action="{{ route('pharmacy.delete', ['id' => $client->id]) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                           @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $client->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this pharmacy?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Facility
                                                            </button>
                                                        </form>
                                                    </span>
                                                        @endrole
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr class="border-top">
                                        <td colspan="6">
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="float-right">
                                @role('user')
                                <input type="hidden" id="selectedCheckboxes" name="selectedCheckboxes" value="">
                                <input type="hidden" name="facility_type" value="{{$facility_type}}">
                                <button type="submit" class="btn btn-danger float-end" name="action" value="store_cme_submit">Submit Selected List</button>
                                @endrole
                            </div>

                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#myDataTable').DataTable({
                "paging": false, // Disable pagination
                // Other configurations...
            });
        });
    </script>
    <script>
        document.getElementById('exportButton').addEventListener('click', function() {
            // Collect table data
            var tableData = [];

            document.querySelectorAll('#clientForm table tbody tr').forEach(function(row) {
                var rowData = [];
                row.querySelectorAll('td').forEach(function(cell) {
                    rowData.push(cell.textContent.trim());
                });
                tableData.push(rowData);
            });
            console.log(tableData);
            // Submit table data to the server for Excel export
            fetch('{{ route("pharmacy-users.export_excel") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ tableData: tableData })
            }).then(function(response) {
                // Handle response
                if (response.ok) {
                    // If export is successful, initiate file download
                    response.blob().then(function(blob) {
                        var downloadLink = document.createElement('a');
                        downloadLink.href = window.URL.createObjectURL(blob);
                        downloadLink.setAttribute('download', 'pharmacy_clients.xlsx');
                        downloadLink.style.display = 'none';
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    });
                } else {
                    // Handle error
                    console.error('Export failed');
                    alert('Export failed!');
                }
            }).catch(function(error) {
                console.error('Export error:', error);
                alert('Export error!');
            });
        });
    </script>

@endsection


