@if ($product->reviews->count())
    <div class="mt-4 data-simplebar">
        <div class="simplebar-wrapper">

            <div class="simplebar-mask">
                <div class="simplebar-offset">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content">
                        <div class="simplebar-content">
                            @foreach ($product->reviews as $review)
                                <div class="d-flex p-3 border-bottom border-bottom-dashed">
                                    <div class="flex-grow-1">
                                        <div class="d-flex mb-3">
                                            <div class="flex-grow-1">
                                                <div class="d-flex">
                                                    <h6 class="mb-0 lh-base fs-3">
                                                        {{ $review->user->name }}
                                                    </h6>
                                                    <div class="ms-3 fs-5">
                                                        <select class="ps-rating" data-read-only="true">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <option value="{{ $i <= $review->rating ? 1 : 2 }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="mb-0 text-muted">
                                                    <i class="ri-calendar-event-fill me-2 align-middle"></i>
                                                    {{ $review->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-0">
                                                {{ $review->comment }}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .simplebar-content .br-theme-fontawesome-stars .br-widget a{
            font-size: 16px
        }
    </style>
@endif
