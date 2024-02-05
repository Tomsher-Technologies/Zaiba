<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        @font-face {
            font-family: 'Roboto';
            src: url("{{ static_asset('fonts/Roboto-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: 'Roboto';
            color: #333542;
        }

        body {
            font-size: .875rem;
        }

        .gry-color *,
        .gry-color {
            color: #878f9c;
        }

        .white-color{
            color:#fff !important;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .5rem .7rem;
        }

        table.padding td {
            padding: .7rem;
        }

        table.sm-padding td {
            padding: .2rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: .85rem;
        }

        .currency {}
    </style>
</head>

<body>
    <div>
        @php
            $logo = get_setting('header_logo');
        @endphp
        <div style="background: #1f4932;padding: 1.5rem;">
            <table>
                <tr>
                    <td>
                        <img loading="lazy" src="{{ asset('admin_assets/assets/img/logo-mail.png') }}" height="75"
                                style="display:inline-block;">
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1.2rem;" class="strong white-color">{{ get_setting('site_name') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="white-color small">{{ get_setting('contact_address') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="white-color small"></td>
                    <td class="text-right small"><span class="white-color small">{{ translate('Order ID') }} : </span> <span
                            class="strong white-color strong"><b class="white-color">{{ $order->code }}</b></span></td>
                </tr>
                <tr>
                    <td class="white-color small"></td>
                    <td class="text-right small"><span class="white-color small">{{ translate('Order Date') }} : </span>
                        <span class="white-color strong"><b class="white-color">{{ date('d-m-Y', $order->date) }}</b></span>
                    </td>
                </tr>
            </table>

        </div>

        <div style="padding: 1.5rem;padding-bottom: 0;display: flex;justify-content: space-between;">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">Ship to:</td>
                </tr>
                <tr>
                    <td class="strong">{{ $shipping_address->name ?? ''}}</td>
                </tr>
                <tr>
                    <td class="gry-color small">
                        {{ $shipping_address->address ?? '' }}, <br>
                        {{ $shipping_address->city ?? '' }},
                        {{ $shipping_address->state ?? '' }}, <br>
                        {{ $shipping_address->country ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="gry-color small">Email: {{ $shipping_address->email ?? '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">Phone: {{ $shipping_address->phone ?? '' }}</td>
                </tr>
            </table>
            <table>
                @php
                    $billing_address = json_decode($order->billing_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">Bill to:</td>
                </tr>
                <tr>
                    <td class="strong">{{ $billing_address->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">
                        {{ $billing_address->address ?? '' }}, <br>
                        {{ $billing_address->city ?? '' }},
                        {{ $billing_address->state ?? '' }}, <br>
                        {{ $billing_address->country ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td class="gry-color small">Email: {{ $billing_address->email ?? '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">Phone: {{ $billing_address->phone ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 1.5rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="35%">Product Name</th>
                        <th width="10%">Qty</th>
                        <th width="15%">Unit Price</th>
                        <th width="15%" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                            <tr class="">
                                <td>{{ $orderDetail->product->name }} 
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
                                <td class="gry-color">{{ $orderDetail->quantity }}</td>
                                <td class="gry-color currency">
                                    @if ($orderDetail->og_price != $orderDetail->offer_price)
                                        <del>{{ single_price($orderDetail->og_price) }}</del> <br>
                                    @endif
                                    {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table style="width: 40%;margin-left:auto;" class="text-right sm-padding small strong">
                <tbody>
                    <tr>
                        <th class="gry-color text-left">Sub Total</th>
                        <td class="currency">
                            {{ single_price($order->orderDetails->sum('price')) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="gry-color text-left">
                            Tax
                        </th>
                        <td class="currency">
                            {{ single_price($order->orderDetails->sum('tax')) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="gry-color text-left">Shipping Cost</th>
                        <td class="currency">{{ single_price($order->shipping_cost) }}</td>
                    </tr>
                    @if ($order->coupon_discount)
                        <tr class="border-bottom">
                            <th class="gry-color text-left">Coupon Discount</th>
                            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th class="gry-color text-left">Coupon Code</th>
                            <td class="currency">{{ $order->coupon_code }}</td>
                        </tr>
                    @endif
                    {{-- @if ($order->offer_discount)
                        <tr class="border-bottom">
                            <th class="gry-color text-left">Offer Discount</th>
                            <td class="currency">{{ single_price($order->offer_discount) }}</td>
                        </tr>
                    @endif --}}
                    <tr>
                        <th class="text-left strong">Grand Total</th>
                        <td class="currency">{{ single_price($order->grand_total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>
