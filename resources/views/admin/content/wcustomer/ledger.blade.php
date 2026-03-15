@extends('backend.master')

@section('maincontent')

    <style>
        .select2-container--open {
            z-index: 9999 !important;
        }

        .select2-dropdown {
            z-index: 9999;
        }
    </style>

    <div class="container-fluid pt-4 px-4">

        <div class="pagetitle row">
            <div class="col-6">
                <h1><a href="{{url('/admindashboard')}}">Dashboard</a></h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/admindashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">W-customer</li>
                        <li class="breadcrumb-item active">Ledger</li>
                    </ol>
                </nav>
            </div>

        </div><!-- End Page Title -->


        {{-- //popup modal for Add Payment --}}
        <div class="modal fade" id="mainPurchese" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form name="form" method="post" action="{{route('wcustomerpayment.store')}}">
                            @csrf
                            <input type="text" name="wcustomer_id" value="{{$wcustomer->id}}" hidden>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" class="form-control" id="date" value="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group pb-2">
                                <label for="trx_id">Trx ID</label>
                                <input type="text" name="trx_id" class="form-control" id="trx_id">
                            </div>

                            <div class="form-group pb-2">
                                <label for="quantity">Amount</label>
                                <input type="text" name="amount" class="form-control" id="amount">
                            </div>

                            <div class="form-group pb-2">
                                <label for="payment_type_id">Payment Type</label>

                                <select name="payment_type_id" class="form-control" id="payment_type_id">

                                    @foreach($payment_types as $payment_type)

                                        <option value="{{$payment_type->id}}">{{$payment_type->paymentTypeName}}</option>

                                    @endforeach

                                </select>
                            </div>


                            <div class="form-group pb-2">
                                <label for="comments">Comments</label>
                                <textarea name="comments" class="form-control" id="comments"></textarea>
                            </div>

                            <div class="form-group" style="text-align: right">
                                <div class="submitBtnSCourse">
                                    <button type="submit" name="btn" class="btn btn-primary  btn-block">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div><!-- End popup Modal-->

        {{--      purchase/Wcustomer Ledger--}}

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center pb-3">Wcustomer info</h2>
                    <div class="card">
                        <div class="card-body pt-4 d-flex justify-content-between">
                            <div>
                                <div><span><span
                                                class="fw-bold"> Wcustomer Name:</span> {{ $wcustomer->wcustomerName}}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Wcustomer Phone:</span> {{ $wcustomer->wcustomerPhone  }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Wcustomer Email:</span> {{ $wcustomer->wcustomerEmail }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Wcustomer Address:</span> {{ $wcustomer->wcustomerAddress }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Company Name:</span> {{ $wcustomer->wcustomerCompanyName  }}</span>
                                </div>
                            </div>

                            <div>
                                <div><span><span
                                                class="fw-bold"> Partial Amount</span> {{ $wcustomer->wcustomerPartialAmount }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Total Amount:</span> {{ $wcustomer->wcustomerTotalAmount }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Paid Amount:</span> {{ $wcustomer->wcustomerPaidAmount   }}</span>
                                </div>
                                <div><span><span
                                                class="fw-bold"> Due Amount:</span> {{ $wcustomer->wcustomerDueAmount  }}</span>
                                </div>

                            </div>


                            <div class="" style="text-align: right">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#mainPurchese"><span style="font-weight: bold;">+</span> Add
                                    Payment
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- //table section for category --}}
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center pb-3">Wcustomer Ledger</h2>
                    <div class="card">
                        <div class="card-body pt-4">
                            @if(\Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ \Session::get('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table table-centered table-borderless table-hover mb-0"
                                       id="purcheseinfotbl" width="100%">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>INV</th>
                                        <th>A/C Title</th>
                                        <th>Dabit</th>
                                        <th>Cradit</th>
                                        <th>Balance</th>
                                        <th>Notes</th>
                                        <th>Admin</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $balance=0;
                                            $due=0;
                                            $price=0;
                                        @endphp
                                        @forelse ($orders as $order)
                                            @php
                                                $price=$balance+$order->totalAmount;
                                            @endphp
                                            <tr class="">
                                                <td>{{ $order->invoiceID }}</td>
                                                <td>{{ $order->date }}</td>
                                                <td>Sale</td>
                                                <td> </td>
                                                <td>{{ $order->totalAmount }}</td>
                                                <td>{{ $price }}</td>
                                                <td></td>
                                                <td>
                                                    {{App\Models\Admin::where('id',$order->admin_id)->first()->name}}
                                                </td>
                                            </tr>

                                            @forelse(App\Models\Wpayment::where('wsale_id',$order->id)->get() as $payment)
                                                @php
                                                    $balance=$price-$payment->amount;
                                                    $due=$price-$payment->amount;
                                                    $price=$due;
                                                @endphp
                                                <tr class="">
                                                    <td></td>
                                                    <td>{{ $payment->date }}</td>
                                                    <td>{{App\Models\Paymenttype::where('id',$payment->payment_type_id)->first()->paymentTypeName }} Receive For- #{{ $order->invoiceID }}</td>
                                                    <td>{{ $payment->amount }}</td>
                                                    <td></td>
                                                    <td>{{ $due }}</td>
                                                    <td></td>
                                                    <td>
                                                    {{App\Models\Admin::where('id',$payment->admin_id)->first()->name}}
                                                    </td>
                                                </tr>

                                            @empty
                                                @php
                                                    $balance=$price;
                                                @endphp
                                            @endforelse
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>


    </div>


    @section('subscript')
        <script>
            flatpickr("#date", {});
            flatpickr("#editdate", {});
        </script>
    @endsection

@endsection
