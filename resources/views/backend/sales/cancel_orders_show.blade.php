@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-primary" href="{{ Session::has('last_url') ? Session::get('last_url') : route('cancel_requests.index') }}" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                   
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                @endphp
                {{-- <div class="col-md-4 text-right">
                    Estimated Delivery Date :  <b>{!! ($order->estimated_delivery != NULL && $order->estimated_delivery != '0000-00-00') ? date('d-m-Y', strtotime($order->estimated_delivery)) : '' !!}</b>
                </div> --}}
            </div>
            <div class="mb-3">
                {!! QrCode::size(100)->generate($order->code) !!}
            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">
                    <address>
                        <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                        {{ json_decode($order->shipping_address)->email }}<br>
                        {{ json_decode($order->shipping_address)->phone }}<br>
                        {{ json_decode($order->shipping_address)->address }},
                        {{ json_decode($order->shipping_address)->city }}
                        {{ json_decode($order->shipping_address)->state }}
                        <br>
                        {{ json_decode($order->shipping_address)->country }}
                    </address>

                    <p><b>Order Notes : </b> {{$order->order_notes ?? ''}}</p>
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">Payment Information</strong><br>
                        Name: {{ json_decode($order->manual_payment_data)->name }},
                        Amount: {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        TRX ID: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Status</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Date </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            @if ($order->payment_type == 'card' || $order->payment_type == 'card_wallet')
                                <tr>
                                    <td class="text-main text-bold">Payment Tracking Id</td>
                                    <td class="text-right">
                                        {{ $order->payment_tracking_id }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <ul class="status_indicator">
                <li class="status completed" style="float:left">Delivered</li>
                <li class="status picked_up ml-2" style="float:left">Picked Up</li>
            </ul>
            <br>
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                {{-- <th data-breakpoints="lg" class="text-uppercase">Delivery Type</th> --}}
                                <th  class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th  class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    $statusColor = '#fff';
                                    if ($orderDetail->delivery_status == 'picked_up'){
                                        $statusColor = '#e9ae004f';
                                    }elseif ($orderDetail->delivery_status == 'delivered') {
                                        $statusColor = '#03ff0338';
                                    }
                                    
                                @endphp
                                <tr style="background:{{$statusColor}}">
                                   
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <img height="50" src="{{ get_product_image($orderDetail->product->thumbnail_img, '300') }}">
                                        @else
                                            <strong>N/A</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <strong class="text-muted">{{ $orderDetail->product->name }}</strong>
                                            {{-- <small> --}}
                                               
                                            {{-- </small> --}}
                                        @else
                                            <strong>Product Unavailable</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        @if ($orderDetail->og_price != $orderDetail->offer_price)
                                            <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                        @endif
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">Sub Total :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                {{ single_price($order->shipping_cost) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-download"></i></a>
                </div>
            </div>

        </div>
    </div>

    
    
@endsection

@section('styles')
    <style>
    .status_indicator {
        margin: 0px 0px 20px;
        padding: 0;
        list-style: none;
    }
    .status {
        &.completed:before {
            background-color: #03ff0338;
            border-color: #78D965;
            box-shadow: 0px 0px 4px 1px #94E185;
        }

        &.picked_up:before {
            background-color: #e9ae004f;
            border-color: #FFB161;
            box-shadow: 0px 0px 4px 1px #FFC182;
        }
        &:before {
            content: ' ';
            display: inline-block;
            width: 25px;
            height: 12px;
            margin-right: 10px;
            border: 1px solid #000;
        }
    }
    </style>
@endsection

@section('script')
    <script type="text/javascript">
       
    </script>
@endsection
