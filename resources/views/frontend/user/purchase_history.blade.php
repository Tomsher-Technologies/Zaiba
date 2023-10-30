@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">My Account</a></li>
                <li>My Orders History</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row">
                    @include('frontend.partials.dashboard.sidebar')
                    <div class="col-xxl-8 col-lg-8">
                        <div class="dashboard-right-sidebar">
                            <div class="tab-content">
                                <div class="">
                                    <div class="dashboard-home">
                                        <div class="title">
                                            <h4>My Orders History</h4>
                                        </div>
                                        <div id="order-history">
                                            <div class="card-table">
                                                <div class="table-responsive-sm">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Order</th>
                                                                <th>Date</th>
                                                                <th>Total</th>
                                                                <th>Status</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @if ($orders->count())
                                                                @foreach ($orders as $order)
                                                                    <tr>
                                                                        <td>
                                                                            <a
                                                                                href="{{ route('purchase_history.details', encrypt($order->id)) }}">
                                                                                #{{ $order->code }}
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            {{ formatDate($order->created_at) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ format_price($order->grand_total) }} for
                                                                            {{ $order->order_details_count }}
                                                                            {{ Str::plural('Item', $order->order_details_count) }}
                                                                        </td>
                                                                        <td>
                                                                            {!! deliveryBadge($order->delivery_status) !!}
                                                                        </td>
                                                                        <td>
                                                                            <a href="#invoiceModal" data-bs-toggle="modal"
                                                                                class="ps-btn medium">Invoice</a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                You have no orders
                                                            @endif


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
