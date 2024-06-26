@php(extract($data))
@extends('layouts.app-v2')

@section('content-v2')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success_message') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card card-default">

        <div class="card-header clearfix">

            <div class="float-start">
                <h4 class="mt-5 mb-5"><a href="{{ route('facility-users.index', ['facility_type' => $facility_type])}}">All Clients</a> | Manage Your {{$pagecategory}} List</h4>
            </div>




        </div>

        @if(count($allclients) == 0)
            <div class="card-body text-center">
                <h4>No {{$pagecategory}} Available.</h4>
            </div>
        @else
        <div class="card-body card-body-with-table">



            <div class="table-responsive">

                    @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Last Visit</th>
                            <th>Class</th>
                            <th>Products</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>



                    @foreach($allclients as $key=> $client)
						@if ($client->facility_type == $facility_type)
                        <?php
                            $visited_today = in_array($client->id, $sales_call_ids);
                            $last_visit_day = $data['last_visit_days']->where('client_id', $client->id)->first();
                            $formatted_last_visit_day = $last_visit_day ? \Carbon\Carbon::parse($last_visit_day->last_visit_day)->format('jS F Y') : 'No visits yet';
                            $days_since_last_visit = $last_visit_day ? \Carbon\Carbon::parse($last_visit_day->last_visit_day)->diffInDays(\Carbon\Carbon::now()) : null;
                            $text_color = $days_since_last_visit !== null && $days_since_last_visit > 10 ? 'red' : 'green';
                            $visit_count = $data['visit_counts']->where('client_id', $client->id)->first();
                            $class = $client->pivot->class; // Assuming you have access to client's class
                            $tick_count = 0;
                            if ($class == 'A') {
                                $tick_count = $visit_count && $visit_count->visit_count > 0 ? 1 : 0;
                            } elseif ($class == 'B') {
                                $tick_count = $visit_count ? min($visit_count->visit_count, 2) : 0;
                            }
                        ?>
                        <form method="post" action="{{ route('personal-facility-users.update') }}">
                            @csrf
                        <tr>
                            <td style="vertical-align: top">
                                {{$loop->iteration}}
                            </td>
                            <td style="vertical-align: top">{{ $client->code }}</td>
                            <td style="vertical-align: top">{{ $client->name }}</td>
                           <td style="vertical-align: top">
                               {{ $formatted_last_visit_day }} <br>
                               @if ($days_since_last_visit !== null)
                                   <span style="color: {{ $text_color }}">
                                    {{ $days_since_last_visit }} days ago
                                    </span>
                               @endif
                               <br>
                               {{ $visit_count ? $visit_count->visit_count : 0 }} Visits
                               (
                               @if ($class == 'A')
                                   @if ($tick_count == 1)
                                       <span style="color: green;">&#10004;</span> <!-- Green tick -->
                                   @else
                                      <span style="color: grey;">&#10004;</span> <!-- Grey tick -->
                                   @endif
                               @else
                                   @for ($i = 0; $i < 2; $i++)
                                       @if ($i < $tick_count)
                                           <span style="color: green;">&#10004;</span> <!-- Green tick -->
                                       @else
                                           <span style="color: grey;">&#10004;</span> <!-- Grey tick -->
                                       @endif
                                   @endfor
                               @endif
                               )
                           </td>
                            <td style="vertical-align: top">
                                <select class="form-control" id="class" name="class">
                                    @if ($client->pivot->class == null)
                                        <option value="" selected>Select Class</option>
                                    @endif
                                    @foreach($classes as $class)
                                        @if ($client->pivot->class == $class)
                                            <option class="form-control" value="{{$class}}" selected>{{$class}}</option>
                                        @else
                                            <option class="form-control" value="{{$class}}">{{$class}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                                <?php
                                $keyname = "collapse".$key;
                                ?>
                            <td>
                                <?php
                                $user_prods_array = [];
                                if ($client->pivot->product_ids != null)
                                    $user_prods_array = json_decode($client->pivot->product_ids);
                                ?>

                                <select class="form-control" id="products" name="products[]" multiple>
                                    @foreach($products as $product)
                                        @if(in_array($product->id, $user_prods_array))
                                            <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                                        @else
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endif
                                    @endforeach
                                </select>



                            </td>
                            <td style="vertical-align: top"><input type="hidden" name="client_id" value="{{$client->id}}"><button type="submit" class="btn btn-primary" name="action">Update</button></td>
                        </tr>
                        </form>
						@endif
                    @endforeach
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
                    <div class="float-right">

                    </div>



            </div>
        </div>

        <div class="card-footer">
        </div>

        @endif

    </div>
@endsection
