@php(extract($data))
@extends('layouts.app-v2',['title'=>$pagetitle])
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<script src="{{ asset('js/modal.js') }}" defer></script>
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>General uploads</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('salescalls.list-pharmacy')}}">General Uploads</a><i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$pagetitle}}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title fw-semibold">{{$pagetitle}}</h5>
            </div>
            <div class="card-body">

                <div class="card">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="container">
                                <form method="GET" action="{{route('salescalls.view-general-uploads')}}">
                                    <div class="row">
                                        <label for="user_id" class="col-form-label col-2">Select Date Range</label>
                                        <div class="col-5">

                                            <div class="input-group">
                                                <input type="date" id="filter_date" class="form-control" name="filter_date" value="{{$filter_date}}">
                                                <input type="date" id="end_date" class="form-control" name="end_date" value="{{$end_date}}">
                                                <input type="hidden" name="filter" value="is_on">
                                                <button class="btn btn-light-info text-info font-medium" type="submit">Go!</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <hr>
                            <div class="row">
                                @if (count($uploads) > 0)
                                    @foreach($uploads as $index => $upload)
                                        @if($upload->image_path != null)
                                            <div class="col-md-4">
                                                <figure>
                                                    <img src="{{ $upload->image_path }}" id="img{{ $index }}" alt="Sample slip Image" class="img-fluid myImg" style="width: 300px; height: 200px" data-toggle="modal" data-target="#myModal{{ $index }}">
                                                    <figcaption>
                                                        <b>Customer Name:</b> {{ $upload->customer_name }} <br>
                                                        <b>Location:</b> {{ $upload->location->name }} <br>
                                                        <b>Notes:</b> {{ $upload->notes ? implode(' ', array_slice(str_word_count($upload->notes, 1), 0, 10)) : 'N/A' }} <br>
                                                        <b>Posted On:</b> {{ \Carbon\Carbon::parse($upload->created_at)->format('jS M Y g:ia') }}
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <!-- Modal body with image -->
                                                        <div class="modal-body" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
                                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 10px;  background-color: white;"></button>
                                                            <button type="button" class="btn btn-primary" onclick="rotateImage('{{ $index }}')" style="position: absolute; top: 10px; left: 10px;">Rotate</button>
                                                            <img src="{{ $upload->image_path }}" class="img-fluid" style="max-height: 600px; margin: auto; display: block;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-md-12">
                                        <h6 class="fw-semibold mb-0">No General Uploads Have been Posted</h6>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            function rotateImage(index) {
                var modal = document.getElementById('myModal' + index);
                var image = modal.querySelector('.modal-body img');
                var currentRotation = parseFloat(image.getAttribute('data-rotation')) || 0;
                currentRotation += 90;
                image.style.transform = 'rotate(' + currentRotation + 'deg)';
                image.setAttribute('data-rotation', currentRotation);
            }
        </script>

@endsection
