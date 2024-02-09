<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 0;
            /* padding: 20px; */
        }

        .invoice {
            width: 100%;
            /* margin: 0 auto; */
            /* border: 1px solid #ccc; */
            /* padding: 20px; */
            /* border-radius: 6px; */

        }

        .invoice-header {
            width: 100%;
            height: 50px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-header h1 {
            margin: 0;
            width: 50%;
            float: right;
            text-align: right;
        }

        .invoice-info {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 35px;
            height: 70px;
        }

        .invoice-info-left {
            text-align: left;
            width: 50%;
            float: left;
        }

        .invoice-info-right {
            width: 50%;
            text-align: right;
            float: right;

        }

        .invoice-address {
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            height: 250px;
        }

        .invoice-address p {
            margin: 5px 0;
            color: #666;
            line-height: 1.5;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;

        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .invoice-total {
            text-align: right;
        }

        .invoice-logo {
            /* width: 50%; */
            /* max-width: 150px; */
            margin-right: 20px;
            float: left;
            text-align: left;
        }

        .invoice-table .theader {
            background: #f5f6fa;

        }

        th {
            padding: 10px 15px;
            line-height: 1.55em;
        }

        td {
            padding: 10px 15px;
            line-height: 1.55em;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <div class="invoice-header">
            <img width="140" src="{{ $imagePath }}" alt="Logo" class="invoice-logo">
            <h1>Invoice</h1>
        </div>

        <div class="invoice-info">
            <div class="invoice-info-left">
                <strong>Invoice Number:</strong> {{ $order->code }} <br>
                <hr style="border: 0.5px solid #eee">
                <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                <br>
                <hr style="border: 0.5px solid #eee">
                <strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->shipping_type)) }}
            </div>

            <div class="invoice-info-right">
                <strong>Date:</strong> {{ date('d M Y, h:i:a', $order->date) }} <br>
                <hr style="border: 0.5px solid #eee">
                <strong>Delivery Status:</strong>
                {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }} <br>
                <hr style="border: 0.5px solid #eee">
                <strong>&nbsp;</strong>
            </div>
        </div>

        <hr style="border: 0.5px solid #eee">

        <div class="invoice-address">
            <div class="invoice-info-left">
                <strong>Billing Address:</strong>
                <p>
                    @php 
                        $billingAddress = json_decode($order->billing_address);
                    @endphp
                    {{ $billingAddress?->name }} <br>
                    {{ $billingAddress?->address }}<br>
                    {{ $billingAddress?->city }}<br>
                    {{ $billingAddress?->state }}<br>
                    {{ $billingAddress?->country }}<br>
                    {{ $billingAddress?->phone }}<br>
                    {{ $billingAddress?->email }}<br>
                </p>
            </div>
            <div class="invoice-info-left" style="float: right; text-align:right">
                <strong>Shipping Address:</strong>
                <p>
                    @php 
                        $shippingAddress = json_decode($order->shipping_address);
                    @endphp
                    {{ $shippingAddress?->name }}<br>
                    {{ $shippingAddress?->address }} <br>
                    {{ $shippingAddress?->city }} <br>
                    {{ $shippingAddress?->state }} <br>
                    {{ $shippingAddress?->country }} <br>
                    {{ $shippingAddress?->phone }} <br>
                    {{ $shippingAddress?->email }} <br>
                </p>
            </div>
        </div>

        <table class="invoice-table">
            <thead class="theader">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderDetails as $key => $orderDetail)
                    <tr>
                        <td class="text-start">
                            <span class="fw-medium">
                                {{ $orderDetail->product->name }}
                            </span>
                            @if ($orderDetail->variation != null)
                                @php
                                    $variations = json_decode($orderDetail->variation);
                                    
                                @endphp
                                <ul>
                                    @foreach($variations as $var)
                                        <li> {{ $var->name ?? '' }} : {{ $var->value ?? '' }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="gry-color currency">
                            @if ($orderDetail->og_price != $orderDetail->offer_price)
                                <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                            @endif
                            {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                        <td class="gry-color">{{ $orderDetail->quantity }}</td>
                        <td class="text-right currency">
                            {{ single_price($orderDetail->price) }}
                        </td>
                       
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="invoice-total">
            <p><strong>Subtotal:</strong> {{ single_price($order->orderDetails->sum('price')) }}</p>
            <p><strong>Tax:</strong> {{ single_price($order->orderDetails->sum('tax')) }}</p>
            <p><strong>Discount:</strong> {{ single_price($order->coupon_discount) }}</p>
            <p><strong>Shipping Charge:</strong> {{ single_price($order->shipping_cost) }}</p>
            <p><strong>Grand Total:</strong> {{ single_price($order->grand_total) }}</p>
        </div>
    </div>
</body>

</html>
