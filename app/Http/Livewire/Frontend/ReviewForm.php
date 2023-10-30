<?php

namespace App\Http\Livewire\Frontend;

use App\Models\OrderDetail;
use App\Models\Review;
use Auth;
use Livewire\Component;

class ReviewForm extends Component
{
    public $commentable = false;

    public $rating = 1;
    public $comment = '';
    public $product_id;
    public $user_id;

    public function mount($product)
    {
        $this->product_id = $product;
        if (Auth::check() && !isAdmin()) {
            $user_id = $this->user_id = Auth::id();
            $review_count = Review::where('user_id', $this->user_id)
                ->where('product_id', $this->product_id)->count();

            $purchases_count = OrderDetail::where([
                'product_id' => $this->product_id,
                'delivery_status' => 'delivered',
            ])->whereHas('order', function ($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })->count();

            if ($purchases_count > 0 && $review_count == 0) {
                $this->commentable = true;
            }
        }
    }

    public function save()
    {
        if ($this->commentable) {
            $review = Review::create([
                'product_id' => $this->product_id,
                'user_id' => $this->user_id,
                'rating' => $this->rating,
                'comment' => $this->comment,
                'status' => 0,
                'viewed' => 0,
            ]);
        }
        $this->commentable = false;
        
    }

    public function render()
    {
        // if (Auth::check() && Auth::user()->user_type == 'customer') {
        //     $this->commentable = true;
        // }
        return view('livewire.frontend.review-form');
    }
}
