@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>General Uploads</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a><i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">General Uploads</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('salescalls.generalUploads') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="customer_name" class="form-label"><b>Customer Name</b></label>
                                            <input type="text" id="customer_name" name="customer_name" placeholder="Customer Name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="location" class="form-label"><b>Location</b></label>
                                            <select class="form-control" id="location" name="location">
                                                <option value="" disabled selected>Select a Location</option>
                                                @foreach($data['locations'] as $location)
                                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-md-6">
                                            <label for="notes" class="form-label"><b>Notes</b></label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="image" class="form-label"><b>Image Upload</b></label>
                                            <div class="drop-zone" onclick="triggerFileInput()">
                                                <p>Click here to upload the Image</p>
                                                <input type="file" name="image" id="image" onchange="previewImage(event)">
                                                <img src="" id="preview">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-success" name="action" value="item_submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">My Uploads</h5>
                    <div class="card">
                        <div class="card-body">
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
                                                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 10px; right: 10px;"></button>
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
        </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .drop-zone {
            border: 2px dashed #ccc;
            border-radius: 5px;
            cursor: pointer;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .drop-zone input[type="file"] {
            display: none;
        }
        .drop-zone img {
            max-width: 300px;
            display: none;
        }
    </style>
    <script>
        function triggerFileInput() {
            document.getElementById('image').click();
        }

        function previewImage(event) {
            var input = event.target;
            var preview = document.getElementById('preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
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
