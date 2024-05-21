@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>My Sales Target</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Target</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My targets for <b>{{ $currentYear }}</b></h5>
                <div class="table-dash">
                    <div class="table table-responsive">
                        <table class="table table-responsive mb-0 border-0 datatable custom-table table-striped" id="usersTable">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Product Name</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">1<sup>st</sup> Quarter</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">2<sup>nd</sup> Quarter</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">3<sup>rd</sup> Quarter</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Actions</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($products) > 0)
                                @foreach($products as $product )
                                    <tr>
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ ($products->currentPage()-1) * $products->perPage() + $loop->iteration }} </h6></td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $product->name }}</span>
                                        </td>
                                        @for ($quarter = 1; $quarter <= 3; $quarter++)
                                            <td>
                                                @php
                                                    $target = $product->targets->firstWhere('quarter', $quarter);
                                                @endphp

                                                @if ($target)
                                                    <span class="fw-normal">{{ $target->target }}</span>
                                                @else
                                                    <span class="text-success">0</span>
                                                @endif
                                            </td>
                                        @endfor
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('targets.set',['id' =>$product->id]) }}"><i class="fas fa-square-plus" style="color:green; font-size: 18px;"></i> Set Target</a>
{{--                                                    <a class="dropdown-item" href="{{ route('targets.edit',['id' =>$product->id]) }}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit Target</a>--}}
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No Products Check If you are Assigned to a Team </h6></td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr class="border-top">
                                <td colspan="12">
                                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                        <div class="flex justify-between flex-1 sm:hidden">
                                            <a href="{{ $products->previousPageUrl() }}" class="pagination-link" @if (!$products->onFirstPage()) rel="prev" @endif>
                                                « Previous
                                            </a>
                                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                                <a href="{{ $products->url($i) }}" class="pagination-link @if ($i == $products->currentPage()) font-bold @endif">
                                                    {{ $i }}
                                                </a>
                                            @endfor
                                            <a href="{{ $products->nextPageUrl() }}" class="pagination-link" @if ($products->hasMorePages()) rel="next" @endif>
                                                Next »
                                            </a>
                                        </div>

                                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p class="pagination-info">
                                                    Showing
                                                    <span class="font-medium">{{ $products->firstItem() }}</span>
                                                    to
                                                    <span class="font-medium">{{ $products->lastItem() }}</span>
                                                    of
                                                    <span class="font-medium">{{ $products->total() }}</span>
                                                    results
                                                </p>
                                            </div>
                                            <div class="pagination-links">

                                            </div>
                                        </div>
                                    </nav>
                                </td>
                            </tr>
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
