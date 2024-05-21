@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Users</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>  <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-header pb-0">
                    <h4 class="card-title d-inline-block">User Management </h4>
                    <a href="{{route('users.create')}}" class="btn btn-success float-end" role="button" aria-disabled="true">
                        <i class="fas fa-plus" style="color:white; font-size: 16px;"></i>
                        Create
                        <i class="fas fa-user" aria-hidden="true" style="color:white; font-size: 18px;"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-dash">
                    <div class="table table-responsive">
                        <table class="table table-responsive mb-0 border-0 datatable custom-table table-striped" id="usersTable">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">FullName</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Email</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Role</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Status</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Team</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Actions</h6>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($users) > 0)
                                @foreach($users as $user)
                                    <tr>
                                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0"> {{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}</h6></td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $user->first_name }} {{ $user->last_name }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $user->email }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ implode(', ', $user->getRoleNames()->toArray()) }}</span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">
                                                @if($user->active_status == 1)
                                                    <span class="status-green" style="padding:4px; border-radius:8px">Active</span>
                                                @else
                                                    <span class="status-red" style="padding:4px; border-radius:8px">Disabled</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td class="border-bottom-0">
                                            <span class="fw-normal">{{ $user->team->name ?? 'Admin' }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{route('users.edit', ['id' => $user->id])}}"><i class="fas fa-edit" style="color:deepskyblue; font-size: 18px;"></i>Edit User</a>
                                                    <a class="dropdown-item" href="{{route('users.create')}}"><i class="fas fa-square-plus" style="color:green; font-size: 18px;"></i> Create User</a>
                                                    <span class="dropdown-item">
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                            @method('DELETE')

                                                            <div class="icon-container">
                                                                <button type="submit" class="btn btn-link dropdown-item" style="padding: 0;">
                                                                    <i class="fa fa-trash" aria-hidden="true" style="color:red; font-size: 18px;"></i> Delete User
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
                                    <td class="border-bottom-0" colspan=6><h6 class="fw-semibold mb-0">No Users </h6></td>
                                </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr class="border-top">
                                <td colspan="12">
                                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                        <div class="flex justify-between flex-1 sm:hidden">
                                            <a href="{{ $users->previousPageUrl() }}" class="pagination-link" @if (!$users->onFirstPage()) rel="prev" @endif>
                                                « Previous
                                            </a>
                                            @for ($i = 1; $i <= $users->lastPage(); $i++)
                                                <a href="{{ $users->url($i) }}" class="pagination-link @if ($i == $users->currentPage()) font-bold @endif">
                                                    {{ $i }}
                                                </a>
                                            @endfor
                                            <a href="{{ $users->nextPageUrl() }}" class="pagination-link" @if ($users->hasMorePages()) rel="next" @endif>
                                                Next »
                                            </a>
                                        </div>

                                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                            <div>
                                                <p class="pagination-info">
                                                    Showing
                                                    <span class="font-medium">{{ $users->firstItem() }}</span>
                                                    to
                                                    <span class="font-medium">{{ $users->lastItem() }}</span>
                                                    of
                                                    <span class="font-medium">{{ $users->total() }}</span>
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
