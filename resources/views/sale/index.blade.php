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
                <h1>Sale File Upload</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Sale file UploadS</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
        <div class="card-body">
                  <h5 class="card-title">File Upload</h5>

                  <form action="{{ route('sale.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="file" class="col-lg-2 col-form-label">Choose Excel File</label>
                        <div class="col-lg-6">
                            <input type="file" name="file" id="file" class="form-control mb-2">
                        </div>
                    </div>
                      <div class="p-4">
                          <div class="form-group row">
                              <div class="offset-lg-2 col-lg-4">
                                  <button type="submit" class="btn btn-primary">Import</button>
                              </div>
                          </div>
                      </div>


                </form>

                </div>
        </div>
</div>
@endsection
