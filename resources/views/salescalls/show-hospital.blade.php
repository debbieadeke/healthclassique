@php(extract($data))
@extends('layouts.app-v2',['title'=>$title])
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
						<li class="breadcrumb-item"><a href="{{route('salescalls.list-doctor')}}">Clinic Sales Calls</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">View Sales Call</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
			<div class="card-header">
                <h5 class="card-title fw-semibold">View Sales Call</h5>
             </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
							<div class="form-body">
							<!-- Select Clinic -->
							<div class="row">
								<div class="col-6">
									<!-- Double Call (Drop-down) -->
									<div class="mb-3">
										<label for="client_id" class="form-label"><b>Clinic</b></label>
										<input type="text" id="client_id" class="form-control readonly-label" value="{{$salescall->facility->name}}" readonly>
									</div>
								</div>
                                <div class="col-3">
									<!-- Start Time -->
									<div class="mb-3">
										<label for="start_time" class="form-label"><b>Date of Visit</b></label>
										<input type="text"  class="form-control readonly-label" id="start_time" name="start_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('dS F Y')
                                    }}" readonly>
									</div>
								</div>
                                <div class="col-3">
									<!-- End Time -->
									<div class="mb-3">
										<label for="end_time" class="form-label"><b>Time of Visit</b></label>
										<input type="text" class="form-control readonly-label" id="end_time" name="end_time" value="{{
                                        \Carbon\Carbon::parse($salescall->start_time)->format('g:ia')
                                    }} - {{
                                        \Carbon\Carbon::parse($salescall->end_time)->format('g:ia')
                                    }}" readonly>
									</div>
								</div>
							</div>
							<div class="row">
                                  <div class="col-md-12">
								</div>
							</div>
                            @foreach($salescall->salescalldetails as $salescalldetail)
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>Doctor's Name</b></label>
                                            <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{$salescalldetail->titles->name}}.  {{$salescalldetail->first_name}} {{$salescalldetail->last_name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label"><b>Doctor's Speciality</b></label>
                                            <input type="text" class="form-control readonly-label" id="first_name" name="first_name" value="{{$salescalldetail->specialities->name}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="discussion_summary" class="form-label"><b>Discussion Summary & PD Inventory</b></label>
                                            <textarea class="form-control readonly-label" id="discussion_summary" name="discussion_summary" rows="4" readonly>{{$salescalldetail->discussion_summary}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                            </div>

                                <div class="row">
                                    <?php
                                        $filteredProducts = $salescall->productSample->where('sales_call_detail_id', $salescalldetail->id);
                                    ?>
                                    @if ($filteredProducts->isNotEmpty())
                                        @foreach ($filteredProducts as $key => $product_sample)
                                            <div class="col-6">
                                                <div class="row g-1">
                                                    <div class="col-8">
                                                        <div class="mb-3">
                                                            <label for="sample_{{ $key }}" class="form-label"><b>Sample</b></label>
                                                            <input type="text" class="form-control readonly-label" id="sample_{{ $key }}" name="sample[]" value="{{ $product_sample->product->name }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="mb">
                                                            <label for="quantity_{{ $key }}" class="form-label"><b>Quantity</b></label>
                                                            <input type="text" class="form-control readonly-label" id="quantity_{{ $key }}" name="quantity[]" value="{{ $product_sample->quantity }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-12">
                                            No product samples given for this sales call detail
                                        </div>
                                    @endif
                                    <div class="row pt-4">
                                            <?php
                                            $filteredSamples = $salescall->sampleSlip->where('sales_call_detail_id', $salescalldetail->id);
                                            ?>
                                        @if ($filteredSamples->isNotEmpty())
                                            @foreach ($filteredSamples as $sample)
                                                <div class="col-6">
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <div class="row pt-4">
                                                                <figure>
                                                                    <figure>
                                                                        @if ($sample->image_source === 'cloudinary' && $sample->sample_slip_image_url)
                                                                            <img src="{{ $sample->sample_slip_image_url }}" id="smpImg" alt="Sample slip Image" class="img-fluid myImg" style="width: 300px; height: 200px" data-bs-toggle="modal" data-bs-target="#myModal{{ $loop->index }}">
                                                                        @else
                                                                            @if ($salescall->image_source === 'spatie')
                                                                                <img src="{{ url($salescall->getFirstMediaUrl('sample')) }}" id="sampleImg" alt="Sample Image" class="img-fluid myImg" style="width: 300px; height: 200px" data-bs-toggle="modal" data-bs-target="#myModal{{ $loop->index }}">
                                                                            @endif
                                                                        @endif
                                                                        <figcaption>Posted By: {{ $salescall->salesperson->first_name }} {{ $salescall->salesperson->last_name }} <br>Posted On: {{ \Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia') }}</figcaption>
                                                                    </figure>
                                                                </figure>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal for each sample -->
                                                    <div class="modal fade" id="myModal{{ $loop->index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <!-- Modal body with image -->
                                                                <div class="modal-body" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
                                                                    <button type="button" class="btn-close" data-dismiss="modal" onclick="closeModal('myModal{{ $loop->index }}')" aria-label="Close" style="position: absolute; top: 10px; right: 10px;"></button>
                                                                    <button type="button" class="btn btn-primary"   onclick="rotateImage('{{ $loop->index }}')" style="position: absolute; top: 10px; left: 10px;">Rotate</button>
                                                                    <img src="{{ $sample->sample_slip_image_url }}" class="img-fluid" style="max-height: 600px; margin: auto; display: block;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="myModal{{ $loop->index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <!-- Modal header with close button -->
                                                                <div class="modal-header">
                                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <!-- Modal body with image -->
                                                                <div class="modal-body">
                                                                    <img src="{{ $sample->sample_slip_image_url }}" class="img-fluid" style="max-height: 600px; margin: auto; display: block;">
                                                                    <button type="button" class="btn btn-primary" onclick="rotateImage('{{ $loop->index }}')">Rotate</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endforeach
                                            @else
                                        @endif

                                            </div>
                                    <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                            </div>
                                        </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><b>Order Booking</b></h4>
                                    </div>
                                </div>
                                @if ($salescall->pharmacy_order_booked == "Yes")
                                <figure>
                                    @if($salescall->image_source === 'cloudinary' && $salescall->pob_image_url)
                                        <img src="{{ $salescall->pob_image_url }}" id="pobsImg" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px" data-bs-toggle="modal" data-bs-target="#orderBookingModal">
                                    @elseif($salescall->image_source === 'spatie')
                                        <img src="{{ url($salescall->getFirstMediaUrl('order_booked')) }}" id="pobsImg" alt="Order Booking Image" class="img-fluid myImg" style="width: 300px; height: 200px" data-bs-toggle="modal" data-bs-target="#orderBookingModal">
                                    @endif
                                    <figcaption>Posted By: {{$salescall->salesperson->first_name}} {{$salescall->salesperson->last_name}} <br>Posted On: {{\Carbon\Carbon::parse($salescall->created_at)->format('jS M Y g:ia')}}</figcaption>
                                </figure>
                                @else
                                    <div class="row">
                                        <div class="col-3">
                                            Not Done
                                        </div>
                                        <div class="col-9">
                                            <b>Reason:</b> {{$salescall->pharmacy_reasons_for_not_booking}}
                                        </div>
                                    </div>
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="orderBookingModal" tabindex="-1" aria-labelledby="orderBookingModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="orderBookingModalLabel">Order Booking Image</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Add the image here -->
                                                @if($salescall->image_source === 'cloudinary' && $salescall->pob_image_url)
                                                    <img src="{{ $salescall->pob_image_url }}" class="img-fluid" alt="Order Booking Image">
                                                @elseif($salescall->image_source === 'spatie')
                                                    <img src="{{ url($salescall->getFirstMediaUrl('order_booked')) }}" class="img-fluid" alt="Order Booking Image">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="border-1">
                                    </div>
                                </div>
                               <div class="row pt-4">
                                   @role('super_admin')
                                   @if($comments->count() < 0)
                                   <div class="row">
                                       <div class="col-4">
                                           <!-- Button to toggle manager comment textarea -->
                                           <button type="button" class="btn btn-primary" onclick="toggleManagerComment()">Manager Comment</button>
                                       </div>
                                       <div class="col-6">
                                           <!-- Text area for manager comment -->
                                           <textarea class="form-control" id="managerCommentTextArea" rows="3" style="display: none;"></textarea>
                                       </div>
                                       <div class="col-2">
                                           <!-- Submit button -->
                                           <button type="button" class="btn btn-primary" id="managerCommentSubmitButton" onclick="submitManagerComment()" style="display: none;">Submit</button>
                                       </div>
                                   </div>
                                   @endif
                                   @endrole
                               </div>
                                @if($comments->count() > 0)
                                <div class="row">
                                    <section class="rounded-sm" style="background-color: #eee;">
                                        <div class="container my-3 py-3">
                                            <div class="row d-flex">
                                                <div class="col-md-12 col-lg-10 col-xl-8">
                                                    <div class="card">
                                                        @foreach($comments as $comment)
                                                        <div class="card-body">
                                                            <div class="d-flex flex-start align-items-center">
                                                                <div>
                                                                    <h6 class="fw-bold text-primary mb-1">
                                                                       {{ isset($comment['user']['first_name']) && isset($comment['user']['last_name']) ? $comment['user']['first_name'] . ' ' . $comment['user']['last_name'] : "" }} {{ isset($comment['created_at']) ? \Carbon\Carbon::parse($comment['created_at'])->format('M d, Y') : "" }}
                                                                    </h6>
                                                                    <p class="text-muted small mb-0">
                                                                        {{ isset($comment['created_at']) ? \Carbon\Carbon::parse($comment['created_at'])->format('M d, Y') : "" }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <p class="mt-3 mb-4 pb-2">
                                                                {{ isset($comment['comment']) ? $comment['comment'] : "" }}
                                                            </p>
                                                        </div>
                                                        @endforeach
                                                        <div class="card-footer py-3 border-0" style="background-color: #f8f9fa;">
                                                            <div class="d-flex flex-start w-100">
                                                                <div class="form-outline w-100">
                                                                 <textarea class="form-control" id="textAreaExample" rows="4"
                                                                 style="background: #fff;"></textarea>
                                                                    <label class="form-label" for="textAreaExample">Message</label>
                                                                </div>
                                                            </div>
                                                            <div class="float-end mt-2 pt-1">
                                                                <button type="button" class="btn btn-primary btn-sm">Post comment</button>
                                                                <button type="button" class="btn btn-outline-primary btn-sm">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                @endif
                            </div>
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
    html, body {
        height: 100%;
        margin: 0;
    }
    .apexcharts-datalabel-label  {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 13px;
        color: blue;
    }
    @media (max-width:768px) {
        .form-label {
            font-size: 12px;
        }.form-control,.select2-container .select2-selection--single {
             padding: 8px 15px !important;
             min-height: auto;
             border-radius: 7px!important;
             font-size: 10px;
             font-weight: normal;
             line-height: normal;
         }
        .select2-container .select2-selection--single {
            /* border: 2px solid rgba(46, 55, 164, 0.1); */
            border-radius: 10px;
            height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #676767;
            font-size: 11px;
            font-weight: normal;
            line-height: normal;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 0px;
            padding-left: 1px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 29px;
            right: 7px;
        }
        .doctor-content h4 {
            font-size: 16px;
            color: #37429b;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            font-weight: 600;}
    }
    /* Style for chat container */
    .chat-container {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
        max-width: 500px;
        overflow-y: auto; /* Enable vertical scrollbar */
        height: 300px; /* Adjust height as needed */
    }

    /* Style for individual chat messages */
    .chat-message {
        margin-bottom: 10px;
    }

    /* Style for reply input */
    .reply-input {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
    }
</style>
<script>
    // Function to close the modal
    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        if (modal) {
            var modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
                // Remove the backdrop
                var backdrop = document.getElementsByClassName('modal-backdrop')[0];
                backdrop.parentNode.removeChild(backdrop);
                // Remove the modal-open class from body to restore scrolling
                document.body.classList.remove('modal-open');
            }
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
<script>
    // Function to toggle the visibility of the manager comment textarea and submit button
    function toggleManagerComment() {
        var textarea = document.getElementById("managerCommentTextArea");
        var submitButton = document.getElementById("managerCommentSubmitButton");

        textarea.style.display = textarea.style.display === "none" ? "block" : "none";
        submitButton.style.display = submitButton.style.display === "none" ? "block" : "none";
    }

    // Function to handle submission of manager comment
    function submitManagerComment() {
        var comment = $("#managerCommentTextArea").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('salescalls.comments', ['user_id' => $user_id, 'sales_call_id' => $sales_call_id]) }}",
            data: {
                comment: comment
            },
            success: function (response) {
                // Handle success, if needed
                console.log("Comment sent successfully:", response);

                // Clear the textarea after submission
                $("#managerCommentTextArea").val("");
            },
            error: function (error) {
                // Handle error, if needed
                console.error("Error sending comment:", error);
            }
        });
    }
</script>
<script>
    // Sample comments array
    var comments = {!! json_encode($comments) !!};

    // Function to format date as "22nd March 2024"
    function formatDate(dateString) {
        var date = new Date(dateString);
        var options = { day: 'numeric', month: 'long', year: 'numeric' };
        var formattedDate = date.toLocaleDateString('en-US', options);
        var day = date.getDate();
        var suffix = "th";
        if (day === 1 || day === 21 || day === 31) {
            suffix = "st";
        } else if (day === 2 || day === 22) {
            suffix = "nd";
        } else if (day === 3 || day === 23) {
            suffix = "rd";
        }
        return formattedDate.replace(/\d+/, day + suffix);
    }

    // Function to display comments
    function displayComments() {
        var commentContainer = document.getElementById("commentContainer");
        commentContainer.innerHTML = ""; // Clear previous content

        comments.forEach(function(comment) {
            var commentDiv = document.createElement("div");
            commentDiv.classList.add("comment");

            var userIdPara = document.createElement("p");
            userIdPara.innerHTML = "<strong>Name:</strong> " + comment.user.first_name + " " + comment.user.last_name;

            var commentPara = document.createElement("p");
            commentPara.innerHTML = "<strong>Comment:</strong> " + comment.comment;

            var createdAtPara = document.createElement("p");
            createdAtPara.innerHTML = "<strong>Created At:</strong> " + formatDate(comment.created_at);

            commentDiv.appendChild(userIdPara);
            commentDiv.appendChild(commentPara);
            commentDiv.appendChild(createdAtPara);

            commentContainer.appendChild(commentDiv);
        });
    }

    // Call the function to display comments when the page loads
    displayComments();
</script>
@endsection
