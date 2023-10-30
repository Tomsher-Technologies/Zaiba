@extends('backend.layouts.app')
@section('title', 'All Designs')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">

        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">All Designs</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('designs.create') }}" class="btn btn-primary">
                    <span>Add new design</span>
                </a>
            </div>
        </div>
    </div>

    

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">Designs</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_designs" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search"
                                    name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset
                                    placeholder="Type name & Enter">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Image</th>
                                <th>Is Featured</th>
                                <th class="text-right">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designs as $key => $design)
                                <tr>
                                    <td>{{ $key + 1 + ($designs->currentPage() - 1) * $designs->perPage() }}</td>
                                    <td>{{ $design->name }}</td>
                                    <td>{{ $design->slug }}</td>
                                    <td>
                                        <img src="{{ uploaded_asset($design->logo) }}" alt="design"
                                            class="h-50px">
                                    </td>
                                    <td>
                                        {!! $design->is_featured ? '<span class="badge badge-inline badge-success">YES</span>' : '<span class="badge badge-inline badge-danger">NO</span>' !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('designs.edit', $design) }}" title="Edit">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('designs.destroy', $design->id) }}"
                                            title="Delete">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $designs->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
      
    </script>
@endsection
