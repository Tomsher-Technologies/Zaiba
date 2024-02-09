@extends('backend.layouts.app')

@section('content')

<div class="card">
    
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Cancel Requests</h5>
            </div>
        </div>

        <div class="card-body">
            
                <form class="" action="" id="sort_orders" method="GET">
                    <div class="row">
                        
                        <div class="col-lg-3 mt-2">
                            <div class="form-group mb-2">
                                <label>Order Code</label>
                                <input type="text" class="form-control" id="search" name="search"@isset($search) value="{{ $search }}" @endisset placeholder="Type Order code & hit Enter">
                            </div>
                        </div>

                        <div class="col-lg-3 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Approval Status</label>
                                <select id="ca_search" name="ca_search" class="form-control" >
                                    <option {{ ($ca_search == '') ? 'selected' : '' }} value="">Select status</option>
                                    <option {{ ($ca_search == '0') ? 'selected' : '' }} value="10">Pending</option>
                                    <option {{ ($ca_search == '1') ? 'selected' : '' }} value="1">Approved</option>
                                    <option {{ ($ca_search == '2') ? 'selected' : '' }} value="2">Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Date</label>
                                <input type="text" class="aiz-date-range form-control" value="{{ $date }}" name="date" placeholder="Filter by request date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                            </div>
                        </div>

                     

                        <div class="col-auto mt-3">
                            <div class="form-group mb-0" style="margin: inherit;">
                                <button type="submit" class="btn btn-info">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            <hr>

            <table class="table aiz-table mb-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <th>Request Date</th>
                        <th data-breakpoints="xl">Customer</th>
                        <th class="w-25">Reason</th>
                        <th data-breakpoints="xl">Amount</th>
                        <th class="text-center">Request Approval</th>
                        <th class="text-center">Order Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        
                        <tr>
                            <td>
                                {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                            </td>
                            
                            <td>
                                {{ $order->code ?? '' }}
                            </td>

                            <td>
                                {{ ($order->cancel_request_date) ? date('d-m-Y H:i a', strtotime($order->cancel_request_date)) : ''}}
                            </td>

                            <td>
                                {{ $order->user->name }}
                            </td>
                           
                            <td>
                                {{ $order->cancel_reason }}
                            </td>
                            <td>
                                {{ $order->grand_total }}
                            </td>
                           
                            <td class="text-center">
                                @if($order->cancel_approval == 0)
                                    <button class="btn btn-sm btn-success d-innline-block adminApprove" data-id="{{$order->id}}" data-status="1">{{translate('Approve')}}</button>
                                    <button class="btn btn-sm btn-warning d-innline-block adminApprove" data-id="{{$order->id}}" data-status="2">{{translate('Reject')}}</button>
                                @else
                                    @if($order->cancel_approval == 1)
                                        <span class=" badge-soft-success">Approved</span>
                                    @elseif($order->cancel_approval == 2)
                                        <span class=" badge-soft-danger">Rejected</span>
                                    @endif
                                @endif
                            </td>

                            <td class="text-center">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('cancel_orders.show', encrypt($order->id))}}" title="View">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="aiz-pagination">
                {{ $orders->appends(request()->input())->links() }}
            </div>

        </div>
    
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).on("click", ".adminApprove", function(e) {
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            var msg = (status == '1') ? "Do you want to approve this request?" : "Do you want to reject this request?";
            
            swal({
                title: "Are you sure?",
                text: msg,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('cancel-request-status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            status:status,
                            _token: '{{ @csrf_token() }}',
                        },
                        dataType: "html",
                        success: function(response) {
                            if(response == 1){
                                swal("Successfully updated!", {
                                    icon: "success",
                                });
                            }else{
                                swal("Something went wrong!", {
                                    icon: "error",
                                });
                            }
                            setTimeout(function () { 
                                location.reload(true); 
                            }, 3000); 
                        }
                    });
                }else{
                    $(this).val('');
                }
            });
           
        });

       
      
//        function change_status() {
//            var data = new FormData($('#order_form')[0]);
//            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                url: "{{route('bulk-order-status')}}",
//                type: 'POST',
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function (response) {
//                    if(response == 1) {
//                        location.reload();
//                    }
//                }
//            });
//        }

    </script>
@endsection
