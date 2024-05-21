@php(extract($data))
@extends('layouts.app-v2',['pagetitle'=>$pagetitle])

@section('content-v2')
    <div class="container-fluid">
        <div class="card bg-light-info position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Samples</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-muted text-decoration-none" href="{{route('home')}}">Dashboard</a>
                                    <i class="fas fa-angle-right"></i>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Approve
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
            <form action="{{route('sample-batch.update')}}" method="post" id="myForm">
                @csrf
                <div class="col-12 col-md-12  col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="card-title d-inline-block">Approve Sample Requests</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-dash">
                            <div class="table-responsive">
                                <table class="table mb-0 border-0 custom-table" id="sampleBatchTable">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 20%">User</th>
                                        <th style="width: 25%">Product</th>
                                        <th>Quantity <br> Requested</th>
                                        <th>Quantity <br> Approved</th>
                                        <th style="width: 50%"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <div class="form-check check-tables">
                                                    {{$loop->iteration}}
                                                </div>
                                            </td>
                                            <td>
                                                {{$product->user->first_name}} {{$product->user->last_name}}
                                            </td>
                                            <td>
                                                {{$product->product->name}}
                                            </td>
                                            <td>
                                                {{$product->quantity_requested}}
                                            </td>
                                            <td>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control form-control-sm" size="1" name="product_qty[]" value="{{ $product->quantity_requested }}">
                                                </div>

                                                <input type="hidden" name="sample_batch_id[]" value="{{$product->id}}">
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <td></td>
                                    <td colspan="2"></td>
                                    <td colspan="1"></td>
                                    <td colspan="1"><button type="submit" class="btn btn-success" name="action" value="store_cme_submit">Approve Requests</button></td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/libs/jquery.repeater/jquery.repeater.min.js"></script>
    <script src="{{asset('assets/js/repeater-init.js')}}"></script>
    <script>
        function updateProdOrderDetails() {
            const selectElement = document.getElementById("product_setting_id");
            if (selectElement.value === "select_item") {
                document.getElementById("no_of_phases_div").style.display = "none";
                document.getElementById("product_category_div").style.display = "none";
            } else {
                const selectedIndex = selectElement.selectedIndex;
                const selectedOption = selectElement.options[selectedIndex];
                const noOfPhasesInfo = selectedOption.getAttribute("data-extra-no_of_phases");
                const categoryInfo = selectedOption.getAttribute("data-extra-category");

                const inputElement1 = document.getElementById("no_of_phases");
                if (inputElement1) {
                    inputElement1.value = noOfPhasesInfo;
                }
                const inputElement2 = document.getElementById("category");
                if (inputElement2) {
                    inputElement2.value = categoryInfo;
                }
            }
        }
    </script>

    <script>
        const inputField = document.getElementById('product_quantity_target');

        function formatNumber(){
            let inputValue = inputField.value;
            inputValue = inputValue.replace(/[^0-9]/g, '');
            inputValue = Number(inputValue).toLocaleString();
            inputField.value = inputValue;
        }

        inputField.addEventListener('input', formatNumber);
    </script>
@stop
