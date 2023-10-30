@extends('backend.layouts.app')

@section('content')
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">Coupon Information Update</h3>
            </div>
            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ $coupon->id }}" id="id">
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="name">Coupon Type</label>
                        <div class="col-lg-9">
                            <select name="coupon_type" id="coupon_type" class="form-control aiz-selectpicker"
                                onchange="coupon_form()" required>
                                @if ($coupon->type == 'product_base')
                                    )
                                    <option value="product_base" selected>For Products</option>
                                @elseif ($coupon->type == 'cart_base')
                                    <option value="cart_base">For Total Orders</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div id="coupon_form">

                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="name">Is one time use</label>
                        <div class="col-lg-9">
                            <select name="one_time_use" class="form-control aiz-selectpicker" required>
                                <option value="0" {{ $coupon->one_time_use == 0 ? 'selected' : '' }}>
                                    No</option>
                                <option value="1" {{ $coupon->one_time_use == 1 ? 'selected' : '' }}>
                                    Yes</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
            </form>

        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function coupon_form() {
            var coupon_type = $('#coupon_type').val();
            var id = $('#id').val();
            $.post('{{ route('coupon.get_coupon_form_edit') }}', {
                _token: '{{ csrf_token() }}',
                coupon_type: coupon_type,
                id: id
            }, function(data) {
                $('#coupon_form').html(data);

                //    $('#demo-dp-range .input-daterange').datepicker({
                //        startDate: '-0d',
                //        todayBtn: "linked",
                //        autoclose: true,
                //        todayHighlight: true
                // });
            });
        }

        $(document).ready(function() {
            coupon_form();
        });
    </script>
@endsection
