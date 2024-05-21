@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Approve Sales Records</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Approve Sales Records</li>
                        </ol>
                    </nav>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Sales Records</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>No</th>
                                    <th>Customer <br> Code</th>
                                    <th>Customer <br> Name</th>
                                    <th>Product <br> Code</th>
                                    <th>Product <br> Name</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sales as $sale)
                                    <tr style="font-size: 13px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $sale->customer_code }}</td>
                                        <td>{{ $sale->customer_name }}</td>
                                        <td>{{ $sale->product_code }}</td>
                                        <td>{{ $sale->product_name }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td>{{ $sale->date }}</td>
                                        <td>
                                                <?php
                                                $statusClass = $sale->status === 'Pending' ? 'warning' : (  $sale->status === 'Rejected' ? 'danger' : (  $sale->status === 'Approved' ? 'success' : ''));
                                                ?>
                                            <span class="badge bg-{{ $statusClass }}">{{   $sale->status }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('salescalls.edit-reps-sale',['id' => $sale->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i> Edit Sale</a>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('salescalls.destroy-new-sale', ['id' => $sale->id]) }}" method="POST" id="deleteForm{{$sale->id}}">
                                                            @csrf
                                                            @method('POST')
                                                            <input type="hidden" name="id" value="{{ $sale->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" onclick="return confirm('Are you sure you want to delete this Sale?');" style="padding: 0;">
                                                                <i class="fa fa-trash-alt" aria-hidden="true" style="color:red; font-size: 18px;"></i> Dismiss Sale
                                                            </button>
                                                        </form>
                                                    </span>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('salescalls.approve-new-sale', ['id' => $sale->id]) }}" method="POST" id="deleteForm{{$sale->id}}">
                                                            @csrf
                                                            @method('POST')
                                                            <input type="hidden" name="id" value="{{ $sale->id }}"/>
                                                            <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">
                                                                <i class="fas fa-plus" aria-hidden="true" style="color:green; font-size: 18px;"></i> Approve Sale
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
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-body d-flex justify-content-between">
                        <h5 class="card-title fw-semibold mb-4">Approved Sales</h5>
                        <div class="p-4 text-end">
                            <button id="exportButton" class="btn btn-primary btn-sm">Export to Excel</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" id="saleTable">
                            <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" class="text-center">
                                    <th>Customer <br> Code</th>
                                    <th>Customer <br> Name</th>
                                    <th>Product <br> Code</th>
                                    <th>Product <br> Name</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sales_approved as $approved)
                                    <tr style="font-size: 13px">
                                        <td>{{ $approved->customer_code }}</td>
                                        <td>{{ $approved->customer_name }}</td>
                                        <td>{{ $approved->product_code }}</td>
                                        <td>{{ $approved->product_name }}</td>
                                        <td>{{ $approved->quantity }}</td>
                                        <td>{{ $approved->date }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function updateClientCode() {
                // Get the selected option
                var selectedOption = document.getElementById('client_name').options[document.getElementById('client_name').selectedIndex];

                // Get the client Code from the selected option's data attribute
                var clientCode = selectedOption.dataset.clientCode;

                // Set the client type value to the hidden input field
                document.getElementById('client_code').value = clientCode;
            }
            function updateProductCode() {
                // Get the selected option
                var selectedOption = document.getElementById('product_name').options[document.getElementById('product_name').selectedIndex];

                // Get the client Code from the selected option's data attribute
                var productCode = selectedOption.dataset.productCode;

                // Set the client type value to the hidden input field
                document.getElementById('product_code').value = productCode;
            }
        </script>
        <script>
            document.getElementById('exportButton').addEventListener('click', function() {
                console.log("clicked");
                // Collect table data
                var tableData = [];

                document.querySelectorAll('#saleTable table tbody tr').forEach(function(row) {
                    var rowData = [];
                    row.querySelectorAll('td').forEach(function(cell) {
                        rowData.push(cell.textContent.trim());
                    });
                    tableData.push(rowData);
                });
                console.log(tableData);
                // Submit table data to the server for Excel export
                fetch('{{ route("salescalls.export_excel") }}', {
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
                            downloadLink.setAttribute('download', 'sales.xlsx');
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
