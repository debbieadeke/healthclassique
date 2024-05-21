@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">{{$pagetitle}}</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Create
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="{{asset('assets/images/breadcrumb/ChatBc.png')}}" alt="" class="img-fluid mb-n4" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">

                <div class="card card-body">
                    <h5>{{$pagetitle}}</h5>

                    <form action="{{route('input-batch.store')}}" method="post" id="myForm">
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="input_id">Input</label>
                                <select class="form-control" id="input_id" name="input_id" required onchange="updateFormLabels()">
                                    <option value="" selected>Select Input</option>
                                    @foreach($inputs as $input)
                                        <option class="form-control" value="{{$input->id}}" data-extra-type="{{$input->type}}">{{$input->code}} ::  {{$input->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="batch_number">Batch No</label>
                                <input type="text" id="batch_number" name="batch_number" class="form-control" placeholder="" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="supplier_id">Supplier</label>
                                <select class="form-control" id="supplier_id" name="supplier_id" required>
                                    <option>Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option class="form-control" value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="quantity_purchased" id="label_qty_supplied">Quantity Supplied In Grams</label>
                                <input type="text" id="quantity_purchased" name="quantity_purchased" class="form-control" value="1" min="0" required />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="buying_price" id="label_buying_price">Buying Price Per Gram</label>
                                <input type="text" id="buying_price" name="buying_price" class="form-control" value="1" required />
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date" class="form-control" />
                            </div>
                        </div>
                    </div>




                        <div class="mb-3">
                            <button type="submit" class="btn btn-success" name="action" value="save_input_batch">Receive Batch</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
    <script>
        const inputField = document.getElementById('quantity_purchased');

        function formatNumber(){
            let inputValue = inputField.value;
            inputValue = inputValue.replace(/[^0-9]/g, '');
            inputValue = Number(inputValue).toLocaleString();
            inputField.value = inputValue;
        }

        inputField.addEventListener('input', formatNumber);



    </script>

    <script>
        function updateFormLabels() {
            // Get the select element that triggered the change event
            var selectElement = event.target;

            // Get the selected option
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            // Get the data-extra-type attribute from the selected option
            var inputType = selectedOption.getAttribute("data-extra-type");
            
            if (inputType === "packaging") {
                document.getElementById('label_qty_supplied').innerHTML = 'Quantity Supplied';
                document.getElementById('label_buying_price').innerHTML = 'Buying Price Per Piece';
            } else if (inputType === "miscellaneous") {
                document.getElementById('label_qty_supplied').innerHTML = 'Number Available';
                document.getElementById('label_buying_price').innerHTML = 'Cost Per Each';
            }
        }
    </script>
@stop
