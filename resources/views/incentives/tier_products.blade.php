@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body d-flex justify-content-between">
                <div class="">
                    <h1>Tiers Products</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                            <li class="breadcrumb-item active" aria-current="page">Tiers Products</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">{{$pagetitle}}</h5>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('incentive.store-tier-product') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="team" class="form-label"><b>Team</b></label>
                                            <select class="form-control" id="team" name="team">
                                                <option value="" disabled selected>Select Team</option>
                                                @foreach($teams as $team)
                                                    <option value="{{ $team->id }}" data-products="{{ json_encode($team->products) }}">{{ $team->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="tier" class="form-label"><b>Tier</b></label>
                                            <select class="form-control" id="tier" name="tier">
                                                <option value="" disabled selected>Select Tier</option>
                                                <option value="tier1">Tier 1</option>
                                                <option value="tier2">Tier 2</option>
                                                <option value="tier3">Tier 3</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="products" class="form-label"><b>Select Products</b></label>
                                            <select class="form-control" id="products" name="products[]" multiple>
                                                <!-- Products will be populated dynamically based on the selected team -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
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
                    <h5 class="card-title fw-semibold mb-4">Tier Products</h5>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-responsive mb-0 border-0 display">
                                <thead>
                                <tr style="font-size: 14px">
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>Tier</th>
                                    <th>Tier Products</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr style="font-size: 14px">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td >{{ $item->team->name }}</td>
                                        <td >{{ $item->tier }}</td>
                                        <td>
                                            <div>
                                                <ul style="list-style-type: none; padding: 0;">
                                                    @if($item->products)
                                                        @foreach($item->products as $product)
                                                            <li>{{ $product->name }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // When a team is selected
        $('#team').change(function(){
            var selectedTeam = $(this).find(':selected');
            var products = selectedTeam.data('products');

            console.log(products);

            // Clear previous options
            $('#products').empty();

            // Populate products dropdown
            $.each(products, function(index, product){
                $('#products').append('<option value="' + product.id + '">' + product.name + '</option>');
            });
        });
    });
</script>
@endsection
