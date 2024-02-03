@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Coupons')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('coupon.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Coupon')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6">{{translate('Coupon Information')}}</h5>
  </div>
  <div class="card-body">
      <table class="table aiz-table p-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th class="text-center">{{translate('Code')}}</th>
                    <th class="text-center">{{translate('Type')}}</th>
                    <th class="text-center">{{translate('Discount')}}</th>
                    <th class="text-center">{{translate('One Time Use')}}</th>
                    <th class="text-center">{{translate('Start Date')}}</th>
                    <th class="text-center">{{translate('End Date')}}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coupons as $key => $coupon)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td class="text-center">{{$coupon->code}}</td>
                        <td class="text-center">@if ($coupon->type == 'cart_base')
                                Cart Base
                            @elseif ($coupon->type == 'product_base')
                                Product Base
                        @endif</td>
                        
                        <td class="text-center">
                            {{ $coupon->discount }} ({{ ($coupon->discount_type == "percent") ? '%':'AED'}})
                        </td>
                        <td class="text-center">{{ ($coupon->one_time_use == 1) ? 'Yes' :'No' }}</td>
                        <td class="text-center">{{ date('d-m-Y', $coupon->start_date) }}</td>
                        <td class="text-center">{{ date('d-m-Y', $coupon->end_date) }}</td>
						<td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('coupon.edit', encrypt($coupon->id) )}}" title="Edit">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('coupon.destroy', $coupon->id)}}" title="Delete">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
