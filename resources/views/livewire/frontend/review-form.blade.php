<div>
    @if ($commentable)
        <form class="ps-form--review" wire:submit.prevent="save()">
            <h4>Submit Your Review</h4>
            <div wire:ignore class="form-group form-group__rating">
                <label>Your rating of this product</label>
                <select wire:model="rating" class="ps-rating2" data-read-only="false">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="form-group">
                <textarea wire:model="comment" class="form-control" rows="6" placeholder="Write your review here"></textarea>
            </div>
            <div class="form-group submit">
                <button type="submit" class="ps-btn">Submit Review</button>
            </div>
        </form>
    @else
    @endif
</div>
@section('script')
    <script>
        $('select.ps-rating2').barrating({
            theme: 'fontawesome-stars',
            emptyValue: '0',
            onSelect: function(value, text, event) {
                if (typeof(event) !== 'undefined') {
                    @this.set('rating', $(event.target).data('rating-value'));
                }
            }
        });
    </script>
@endsection
