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
        <div class="card card-default">

            <div class="card-header clearfix">

                <div class="float-start">
                    <h4 class="mt-5 mb-5">All Clients
                        @role('user')
                        | <a href="{{ route('personal-client-users.index') }}">Manage My Doctor's List</a></h4>
                    @endrole

                </div>
                <a href="{{ route('client-users.create_two') }}" class="btn btn-success float-end" role="button" aria-disabled="true">
                    <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                    Create
                </a>
            </div>
        </div>
        @if(count($allclients) == 0)
            <div class="card-body text-center">
                <h4>No Clients Available.</h4>
            </div>
        @else
    </div>
    <div class="card">
        <div class="card-body card-body-with-table">
{{--            <div class="p-4 text-end">--}}
{{--                <button id="exportButton" class="btn btn-primary btn-sm">Export to Excel</button>--}}
{{--            </div>--}}
            <div class="table-responsive">
                <form method="post" action="{{ route('client-users.update') }}" id="clientForm">
                    @csrf
                    <div class="p-2">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead>
                            <tr style="font-size: 14px">
                                <th></th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Speciality</th>
                                <th>View <br> More</th>
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
                                        @notrole('user')
                                        {{$loop->iteration}}
                                        @endnotrole
                                    </td>
                                    <td>{{ $client->code }}</td>
                                    <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                                    <td>{{ $client->category }}</td>
                                    <td>{{ $client->locations->name ?? 'N/A' }}</td>
                                    <td>{{ $client->specialities->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="dropdown dropdown-action">
                                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @notrole('user')
                                                <a class="dropdown-item" href="{{route('edit-users.edit_two',['id' => $client->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Client</a>
                                                <span class="dropdown-item">
                                                        <form action="{{ route('client-users.destroy-clients', ['id' => $client->id]) }}" method="POST" id="deleteForm{{$client->id}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="id" value="{{ $client->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Client?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Client
                                                            </button>
                                                        </form>
                                                    </span>
                                                @endnotrole
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <div class="float-right">
                        @role('user')
                        <button type="submit" class="btn btn-danger float-end" name="action" value="store_cme_submit">Submit Selected List</button>
                        @endrole
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-footer">
    </div>
    @endif
    <script>
        document.getElementById('exportButton').addEventListener('click', function() {
            console.log("clicked");
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
                        downloadLink.setAttribute('download', 'clinic_clients.xlsx');
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var selectElement = document.querySelector('select[name="myDataTable_length"]');

            if (selectElement) {
                var options = [10, 25, 50, 100, 200, 500];
                options.forEach(function(option) {
                    var optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    selectElement.appendChild(optionElement);
                });
            }
        });
    </script>
@endsection
