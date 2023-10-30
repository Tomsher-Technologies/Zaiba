@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form class="" id="" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">Countries</h5>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="sort_country" name="sort_country"
                        @isset($sort_country) value="{{ $sort_country }}" @endisset
                        placeholder="Type country name">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table aiz-table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th data-breakpoints="lg">{{ translate('Code') }}</th>
                        <th>{{ translate('Show/Hide') }}</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $key => $country)
                        <tr>
                            <td>{{ $key + 1 + ($countries->currentPage() - 1) * $countries->perPage() }}</td>
                            <td>{{ $country->name }}</td>
                            <td>{{ $country->code }}</td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_status(this)" value="{{ $country->id }}" type="checkbox"
                                        <?php if ($country->status == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <form class="country_rate">
                                    <input type="hidden" name="country_id" value="{{ $country->id }}">
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="number" class="form-control" step="0.01" name="rate"
                                                value="{{ $country->rate }}" placeholder="0.0">
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).on('submit', '.country_rate', function(e) {
            let data = $(this).serialize();
            e.preventDefault();
            $.post('{{ route('countries.rate') }}', {
                _token: '{{ csrf_token() }}',
                data: data,
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Country rate updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });

        });

        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('countries.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Country status updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
