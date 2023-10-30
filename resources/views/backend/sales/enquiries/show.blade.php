@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Enquiry</h5>
                </div>
                <div class="card-body">


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Customer</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Customer" id="name" name="name" class="form-control"
                                value="{{ $enquiry->name }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Email</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Email" id="name" name="name" class="form-control"
                                value="{{ $enquiry->email }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Phone Number</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Phone Number" id="name" name="name" class="form-control"
                                value="{{ $enquiry->phone_number }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Query</label>
                        <div class="col-md-9">
                            <textarea name="" class="form-control" disabled id="" cols="30" rows="10">{{ $enquiry->comment }}</textarea>
                        </div>
                    </div>



                    @if ($enquiry->products)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Products</label>
                            <div class="col-md-9">
                                <table class="table aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th width="20%">Product Name</th>
                                            <th width="20%">Product Variation</th>
                                            <th data-breakpoints="lg">Product SKU</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enquiry->products as $product)
                                            <tr>
                                                <td>
                                                    {{ $product->name }}
                                                </td>
                                                <td>
                                                    {{ $product->pivot->varient ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $product->pivot->sku }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
