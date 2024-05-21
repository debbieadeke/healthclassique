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
                            <li class="breadcrumb-item active" aria-current="page">Privacy Policy Uploads</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('users.privacy_upload') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="document_name" class="form-label"><b>Document Name</b></label>
                                            <input type="text" id="document_name" name="document_name" placeholder="Document Name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="pdf" class="form-label"><b>PDF Upload</b></label>
                                            <div class="drop-zone">
                                                <label for="pdf" class="file-label">
                                                    <p>Click here to upload the PDF</p>
                                                </label>
                                                <input type="file" name="pdf" id="pdf" onchange="previewPdf(event)" accept="application/pdf" style="display: none;">
                                                <!-- The accept attribute specifies the file types that the input accepts -->
                                                <!-- You can specify multiple file types separated by commas, e.g., accept="application/pdf, image/*" -->
                                                <embed src="" id="pdf-preview" type="application/pdf" width="300" height="200">
                                                <!-- The embed element is used to embed PDF documents in HTML -->
                                                <!-- The src attribute will be updated dynamically to show the selected PDF -->
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
                    <h5 class="card-title fw-semibold mb-4">Documents</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @forelse($uploads as $index => $upload)
                                    <div class="col-md-4">
                                        <figure class="document-container">
                                            <embed src="{{ asset($upload->file_path) }}" type="application/pdf" width="100%" height="300px" />
                                            <div class="buttons-container">
                                                <a href="{{ asset($upload->file_path) }}" target="_blank" class="view-pdf-btn">View PDF</a>
                                                <form action="{{ route('users.destroy_privacy_upload', $upload->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="icon-container">
                                                        <button type="submit" class="btn btn-link dropdown-item delete-doc-btn">
                                                            <i class="fa fa-trash-alt" style="color:red; font-size: 12px;" aria-hidden="true"></i> Delete Doc
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <figcaption>
                                                <b>Document Name:</b> {{ $upload->document_name }} <br>
                                            </figcaption>
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
        .document-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .buttons-container {
            display: flex;
            justify-content: center; /* Center the buttons horizontally */
        }

        .view-pdf-btn,
        .delete-form {
            margin: 0 5px; /* Adjust spacing between the buttons */
        }

        .delete-doc-btn {
            padding: 0;
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
