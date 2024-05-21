@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h3>Sample Balance</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Sample Balance</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Rep Samples Balance</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display"  id="myDataTable">
                                <thead>
                                <tr style="font-size: 14px" >
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Quantity in Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($samples as $sample)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td >{{ $sample->product->name }}</td>
                                        <td >{{ $sample->quantity }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
