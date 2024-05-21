@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h3>Privacy policy</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Privacy Policy docs</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Documents</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @forelse($uploads as $index => $upload)
                                    <div class="col-md-4">
                                        <figure class="document-container">
                                            <embed src="{{ asset($upload->file_path) }}" type="application/pdf" width="100%" height="300px" />
                                            <div class="document-info">
                                                <a href="{{ asset($upload->file_path) }}" target="_blank" class="view-document-btn">View Document</a>
                                                <figcaption>
                                                    <b>Document Name:</b> {{ $upload->document_name }}
                                                </figcaption>
                                            </div>
                                        </figure>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <h6 class="fw-semibold mb-0">No documents have been uploaded</h6>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .document-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .document-info {
            margin-top: 10px; /* Adjust spacing as needed */
            text-align: center;
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

        function previewPdf(event) {
            var input = event.target;
            var pdfPreview = document.getElementById('pdf-preview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    pdfPreview.src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
