@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Products</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    List
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{ asset('assets-v2/img/product.png') }}" alt="" height="100px" width="100px" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5 class="card-title fw-semibold mt-2">{{$pagetitle}}</h5>
                    </div>
                    <div class="col-4">
                        <a href="{{route('products.create')}}" class="btn btn-success float-end" role="button" aria-disabled="true">
                            <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                            Create
                        </a>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="">
                        <table class="table table-responsive mb-0 border-0 display" id="myDataTable">
                            <thead class="text-dark fs-4">
                            <tr style="font-size: 14px">
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Product</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Product Code</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Product Price</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Team</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Action</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($products) > 0)
                                @foreach($products as $product)
                                    <tr style="font-size: 13px">
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{  $loop->iteration }}</h6></td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $product->name }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $product->code }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ number_format($product->price, 2, '.', ',') }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $product->team->name }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('products.edit', ['id' => $product->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Product</a>
                                                    <a class="dropdown-item" href="{{route('products.create')}}"><i class="fas fa-square-plus" style="color:green; font-size: 18px;"></i> Create Product</a>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                            @method('DELETE')

                                                        <div class="icon-container">
                                                            <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">
                                                                <i class="fa fa-trash" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete Product
                                                            </button>
                                                        </div>
                                                    </form>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No products entered </h6></td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-container {
            position: relative;
            display: inline-block;
        }

        .icon-container .tooltip {
            visibility: hidden;
            width: 80px;
            background-color: #3676f3;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -40px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .icon-container:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }
        .pagination li {
            margin: 0 5px;
            font-size: 14px;
        }

        .pagination .page-link {
            padding: 5px 10px;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .pagination-link,
        .pagination-link-disabled {
            display: inline-flex;
            items-align: center;
            padding: 8px;
            margin: 5px;
            font-size: 14px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination-link:hover {
            background-color: #f8f9fa;
        }
        .pagination-link {
            text-decoration: none;
            padding: 0.25rem 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
            &:hover {
                background-color: #007bff;
                color: #fff;
                border: 1px solid transparent;
            }
        }
        .font-bold {
            font-weight: bold;
            background-color: #007bff;
            color: #fff;
        }
    </style>

@endsection

