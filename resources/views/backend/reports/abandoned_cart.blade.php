@extends('backend.layouts.app')

@section('content')
    @php
        $used = [];
    @endphp

    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="h6">Abandoned Cart</h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carts as $key => $cart)
                                @if (!in_array($cart->user_id, $used) && !in_array($cart->temp_user_id, $used))
                                    @php
                                        $id = $cart->user_id ?? $cart->temp_user_id;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $cart->user_id ? $cart->user->name : 'GUEST' }}
                                        </td>
                                        <td>{{ $cart->created_at->format('d-m-Y h:i:s A') }}</td>
                                        <td>
                                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                href="{{ route('abandoned-cart.view', $cart->id) }}"
                                                title="View">
                                                <i class="las la-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                        $used[] = $id;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        {{ $carts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
