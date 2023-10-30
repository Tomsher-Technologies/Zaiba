@extends('backend.layouts.app')

@section('content')
    @if (env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
        <div class="">
            <div class="alert alert-danger d-flex align-items-center">
                Please Configure SMTP Setting to work all email sending functionality,
                <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">Configure Now</a>
            </div>
        </div>
    @endif
    @if (Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
        <div class="row gutters-10">
            <div class="col-lg-12 text-right">
                <a href="{{ route('cache.clear', ['type' => 'counts']) }}"
                    class="btn btn-sm btn-soft-secondary btn-circle mr-2 mb-2">
                    <i class="la la-refresh fs-24"></i>
                </a>
            </div>
            
            
            
            <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-1">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/team.png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Customer</div>
                                                    <div class="text-muted mb-1 fs-12 count-n "> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                                             {{ $counts['totalUsersCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
            
            
                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-2">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/Products.png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Products</div>
                                                    <div class="text-muted mb-1 fs-12 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom ">
                                                             {{ $counts['totalProductsCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
                  
                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-3">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/application.png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Product category</div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                                             {{ $counts['categoryCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
            
                     <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-4">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/badge.png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Product brand</div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                                        {{ $counts['brandCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
            
                        <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-5">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/sale (2).png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Sales Amount </div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                                       {{ $counts['salesAmount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
                
                
                
                
                <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-6">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/box (3).png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Orders </div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                               {{ $counts['orderCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
                
                
                
                
                      <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-7">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/shopping-bag (5).png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Completed Orders </div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                                    {{ $counts['orderCompletedCount'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
            
            
            
                     <div class="col-lg-3 col-sm-6 col-md-3 col-xl-3">
                
                   <div class="card custom-card ">
                                        <div class="card-body bg-8">
                                            <div class="row">
                         <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> 
                                                <span class="">
                                                         <img width="50" src="{{ static_asset('assets/img/sign.png') }}">
                                                        </span> 
                                                 </div>
                                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0"
                                                >
                                                    <div class="mb-2 fs-15 count-t">Total Products Sold </div>
                                                    <div class="text-muted mb-1 count-n"> <span
                                                            class="text-dark fw-semibold fs-35 lh-1 vertical-bottom">
                                           {{ $counts['productsSold'] }}
                                                            </span> </div>
                                              
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                </div>
            
            
            
            
            
            
            
            
            
            
            <!--<div class="col-lg-12">-->
            <!--    <div class="row gutters-10">-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Customer-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">-->
            <!--                        {{ $counts['totalUsersCount'] }}-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class="d-block">Total</span>-->
            <!--                        Products-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['totalProductsCount'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Product category-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['categoryCount'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Product brand-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['brandCount'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
           
           
            <!--<div class="col-lg-12">-->
            <!--    <div class="row gutters-10">-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Sales Amount-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">-->
            <!--                        {{ $counts['salesAmount'] }}-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class="d-block">Total</span>-->
            <!--                        Orders-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['orderCount'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Completed Orders-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['orderCompletedCount'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--        <div class="col-3">-->
            <!--            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">-->
            <!--                <div class="px-3 pt-3">-->
            <!--                    <div class="fs-20">-->
            <!--                        <span class=" d-block">Total</span>-->
            <!--                        Products Sold-->
            <!--                    </div>-->
            <!--                    <div class="h3 fw-700 mb-3">{{ $counts['productsSold'] }}</div>-->
            <!--                </div>-->
            <!--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">-->
            <!--                    <path fill="rgba(255,255,255,0.3)" fill-opacity="1"-->
            <!--                        d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z">-->
            <!--                    </path>-->
            <!--                </svg>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    @endif


    @if (Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
        <div class="row gutters-10">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Orders This Month</h6>
                        <a href="{{ route('cache.clear', ['type' => 'orderMonthGraph']) }}"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-1" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Orders Past 12 Months</h6>
                        <a href="{{ route('cache.clear', ['type' => 'orderYearGraph']) }}"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-2" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Total Sales This Month</h6>
                        <a href="{{ route('cache.clear', ['type' => 'salesYearGraph']) }}"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-3" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 fs-14">Total Sales 12 Months</h6>
                        <a href="{{ route('cache.clear', ['type' => 'salesYearGraph']) }}"
                            class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                            <i class="la la-refresh fs-24"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <canvas id="graph-4" class="w-100" height="400"></canvas>
                    </div>
                </div>
            </div>

        </div>
    @endif


    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h6 class="mb-0">Latest User Searches</h6>
            </div>

            <a href="{{ route('cache.clear', ['type' => 'searches']) }}"
                class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                <i class="la la-refresh fs-24"></i>
            </a>

            <a href="{{ route('user_search_report.index') }}" class="btn btn-primary">View All</a>
        </div>
        <div class="card-body">
            <table aria-describedby="" class="table table-bordered aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Search Key</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($searches as $key => $searche)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $searche->query }}</td>
                            <td>
                                @if ($searche->user_id)
                                    <a href="{{ route('user_search_report.index', ['user_id' => $searche->user_id]) }}">
                                        {{ $searche->user->name }}
                                    </a>
                                @else
                                    GUEST
                                @endif
                            </td>
                            <td>{{ $searche->ip_address }}</td>
                            <td>{{ $searche->created_at->format('d-m-Y h:i:s A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Top Selling Products</h6>

            <a href="{{ route('cache.clear', ['type' => 'topProducts']) }}"
                class="btn btn-sm btn-soft-secondary btn-circle mr-2">
                <i class="la la-refresh fs-24"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"
                data-md-items="3" data-sm-items="2" data-arrows='true'>
                @foreach ($topProducts as $key => $product)
                    <div class="carousel-box">
                        <div
                            class="aiz-card-box border border-light rounded shadow-sm hov-shadow-md mb-2 has-transition bg-white">
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img class="img-fit lazyload mx-auto h-210px"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->name }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                            </div>
                            <div class="p-md-3 p-2 text-left">
                                <div class="fs-15">
                                    @if (home_base_price($product) != home_discounted_base_price($product))
                                        <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                    @endif
                                    <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                                </div>
                                <h3 class="fw-600 fs-14 text-truncate-2 lh-1-4 mb-0">
                                    <a href="{{ route('product', $product->slug) }}"
                                        class="d-block text-reset">{{ $product->name }}</a>
                                </h3>
                                <div class="fs-13">
                                    Total sales: {{ $product->order_details_sum_quantity }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        AIZ.plugins.chart('#graph-1', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($days as $day)
                        '{{ $day }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'No:of orders recived this month',
                    data: [
                        {{ $orderMonthGraph['monthOrdersData'] }}
                    ],
                    backgroundColor: [
                        @foreach ($days as $key => $day)
                            'rgba(55, 125, 255, 0.4)',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($days as $key => $day)
                            'rgba(55, 125, 255, 1)',
                        @endforeach
                    ],
                    borderWidth: 1
                }, {
                    label: 'No:of orders completed this month',
                    data: [
                        {{ $orderMonthGraph['monthOrdersCompletedData'] }}
                    ],
                    backgroundColor: [
                        @foreach ($days as $key => $day)
                            'rgba(43, 255, 112, 0.4)',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($days as $key => $day)
                            'rgba(43, 255, 112, 1)',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true,
                            precision: 0
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    }
                }
            }
        });


        AIZ.plugins.chart('#graph-2', {
            type: 'bar',
            data: {
                labels: {!! $orderYearGraph['all']['months'] !!},
                datasets: [{
                    type: 'bar',
                    label: 'No:of orders recived',
                    data: {{ $orderYearGraph['all']['counts'] }},
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(55, 125, 255, 0.4)',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(55, 125, 255, 1)',
                        @endfor
                    ],
                    borderWidth: 1
                }, {
                    type: 'bar',
                    label: 'No:of orders completed',
                    data: {{ $orderYearGraph['completed']['counts'] }},
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(43, 255, 112, 0.4)',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(43, 255, 112, 1)',
                        @endfor
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true,
                            precision: 0
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    }
                }
            }
        });

        AIZ.plugins.chart('#graph-3', {
            type: 'bar',
            data: {
                labels: [
                    @foreach ($days as $day)
                        '{{ $day }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Sales this month',
                    data: [
                        {{ $salesMonthGraph['monthSalesData'] }}
                    ],
                    backgroundColor: [
                        @foreach ($days as $key => $day)
                            'rgba(55, 125, 255, 0.4)',
                        @endforeach
                    ],
                    borderColor: [
                        @foreach ($days as $key => $day)
                            'rgba(55, 125, 255, 1)',
                        @endforeach
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true,
                            precision: 0
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    }
                }
            }
        });

        AIZ.plugins.chart('#graph-4', {
            type: 'line',
            data: {
                labels: {!! $orderYearGraph['all']['months'] !!},
                datasets: [{
                    type: 'line',
                    label: 'Total sales',
                    data: {{ $salesYearGraph['counts'] }},
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(43, 255, 112, 0.4)',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgba(43, 255, 112, 1)',
                        @endfor
                    ],
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        gridLines: {
                            color: '#f2f3f8',
                            zeroLineColor: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10,
                            beginAtZero: true,
                            precision: 0
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            color: '#f2f3f8'
                        },
                        ticks: {
                            fontColor: "#8b8b8b",
                            fontFamily: 'Poppins',
                            fontSize: 10
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontFamily: 'Poppins',
                        boxWidth: 10,
                        usePointStyle: true
                    }
                }
            }
        });
    </script>
@endsection
