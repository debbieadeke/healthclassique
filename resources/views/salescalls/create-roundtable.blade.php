@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Sales Calls</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('salescalls.store') }}">
							@csrf
							<div class="form-body">

							<!-- Select Clinic -->
							<div class="row">
								<div class="col-md-4">
									<div class="mb-3">
										<label for="client_id" class="form-label">Select Doctor</label>
										<select class="form-control select2" style="width: 100%; height: 40px" id="client_id" name="client_id" required onchange="getUserDetails()">
											<option value="" selected>Select Doctor</option>
                                            <option value="add_new">Add New Doctor</option>
											@foreach($clients as $client)
                                                    <?php
                                                    $visited_today = in_array($client->id, $sales_call_ids);
													$in_appointment = in_array($client->id, $appointments_ids);
                                                    ?>
                                            @if (isset($client->specialities))
                                                    <option class="form-control" value="{{$client->id}}" data-extra-info="{{$client->specialities->name}}" data-extra-class="{{$client->class}}">{{$client->titles->name}}.  {{$client->first_name}} {{$client->last_name}}
                                                        @if ($visited_today)
                                                            (Visited today)
                                                        @endif
														@if ($in_appointment)
                                                            (In Appointment)
                                                        @endif
                                                    </option>
                                            @else
                                                    <option class="form-control" value="{{$client->id}}" data-extra-info="None" data-extra-class="{{$client->class}}">{{$client->titles->name}}.  {{$client->first_name}} {{$client->last_name}}
                                                        @if ($visited_today)
                                                            (Visited today)
                                                        @endif
                                                        @if ($in_appointment)
                                                            (In Appointment)
                                                        @endif
                                                    </option>
                                            @endif
                                            @endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3">
                                    <div id="speciality_div" style="flex-direction: column;">
									<label for="speciality" class="form-label">Speciality</label>
									<input type="text" class="form-control" id="speciality" name="speciality" readonly>
                                    </div>
								</div>
								<div class="col-md-1">
                                    <div id="class_div" style="flex-direction: column;">
									<label for="myclass" class="form-label">Class</label>
									<input type="text" class="form-control" id="myclass" name="myclass" readonly>
                                    </div>
								</div>
								<div class="col-md-4">

								</div>
							</div>

                            <div class="row" id="AddDoctorRow" style="display:none; flex-direction: row; background-color: #f8f9fa">
                                <div class="container p-3">
                                    <div class="row">
                                <div class="col-md-1">
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Title</label>
                                        <select class="form-control" id="title_id" name="title_id">
                                            @foreach($titles as $title)
                                                <option class="form-control" value="{{$title->id}}">{{$title->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="newfirstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="newfirstname" name="newfirstname">
                                </div>
                                <div class="col-md-5">
                                    <label for="newlastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="newlastname" name="newlastname">
                                </div>
								<div class="col-md-1">
												&nbsp;
											</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="newclass" class="form-label">Class</label>
                                            <select class="form-control" id="newclass" name="newclass">
                                                <option class="form-control" value="A" selected>A</option>
                                                <option class="form-control" value="B">B</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="newspeciality" class="form-label">Speciality</label>
                                            <select class="form-control" id="newspeciality" name="newspeciality">
                                                <option value="" selected>Select</option>
                                                @foreach($specialities as $speciality)
                                                    <option class="form-control" value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label for="newlocation" class="form-label">Location</label>
                                            <select class="form-control" id="newlocation" name="newlocation">
                                                <option value="" selected>Select</option>
                                                @foreach($newlocations as $newlocation)
                                                    <option class="form-control" value="{{$newlocation->id}}">{{$newlocation->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>





                                <div class="row">
							<!-- Discussion Summary -->
							<div class="mb-3">
								<label for="discussion_summary" class="form-label">Discussion Summary</label>
								<textarea class="form-control" id="discussion_summary" name="discussion_summary" rows="4" required></textarea>
							</div>
                            </div>



                            <div class="row">
                                <div class="col-md-6">
                                  <div class="mb-3">
                                      <label for="first_name" class="form-label">Samples Given</label>
                                      <select class="form-control" id="product_id" name="product_id[]">
                                        <option value="" selected>Select Sample</option>
                                        @foreach($products as $product)
                                              <option class="form-control" value="{{$product->product_id}}">{{$product->product->name}} (Qty: {{$product->quantity}})</option>
                                        @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="mb-3">
                                    <label for="last_name" class="form-label">Quantity</label>
                                        <select class="form-control" id="quantity" name="quantity[]" required>
                                            <option value="0" selected>0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>

                                        </select>
                                  </div>
                                </div>
                              </div>

                            <div class="row">
                                <div class="col-md-6">
                                  <div class="mb-3">
                                      <select class="form-control" id="product_id" name="product_id[]">
                                        <option value="" selected>Select Sample</option>
                                        @foreach($products as $product)
                                             <option class="form-control" value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="mb-3">
                                        <select class="form-control" id="quantity" name="quantity[]" required>
                                            <option value="0" selected>0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>

                                        </select>
                                  </div>
                                </div>
                              </div>

                            <div class="row">
                                <div class="col-md-6">
                                  <div class="mb-3">
                                      <select class="form-control" id="product_id" name="product_id[]">
                                        <option value="" selected>Select Sample</option>
                                        @foreach($products as $product)
                                             <option class="form-control" value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="mb-3">
                                        <select class="form-control" id="quantity" name="quantity[]" required>
                                            <option value="0" selected>0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>

                                        </select>
                                  </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Next Planned Visit -->
                                    <div class="mb-3">
                                      <label for="next_planned_visit" class="form-label">Next Planned Visit</label>
                                      <div class="input-group">
                                        <input type="date" class="form-control" value="" name="next_planned_visit">
                                        <input type="time" class="form-control" value="08:30:00" name="next_planned_time">
                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-5">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-5">
                                  <div class="form-actions">
                                  <div class="text-end align-baseline">
                                    <input type="hidden" name="client_type" value="RoundTable">
                                    <input type="hidden" name="longitude" id="longitude" value="">
                                    <input type="hidden" name="latitude" id="latitude" value="">
                                    <input type="hidden" name="start_time" value="{{$start_time}}">
                                    <button type="submit" class="btn btn-primary">Submit & End Call</button>
                                    <button type="reset" class="btn btn-light-danger text-danger font-medium">Reset</button>
                                  </div>
                                  </div>
                                </div>
                            </div>



						</form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
