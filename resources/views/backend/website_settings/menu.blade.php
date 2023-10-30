@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">Menu</h6>
                </div>
                <div class="card-body">
                    {!! Menu::render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        #update-nav-menu input[type=text],
        #update-nav-menu label,
        #hwpwrap .description-wide,
        #hwpwrap .menu-item-settings,
        #hwpwrap .menu-item-bar .menu-item-handle {
            width: 100% !important
        }

        #hwpwrap .description-thin {
            width: 48% !important
        }
    </style>
@endsection
@section('script')
    {!! Menu::scripts() !!}
@endsection
