<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Cart;
use Auth;
use Cache;
use Livewire\Component;

class MiniCart extends Component
{
    public $carts;
    public $user_col;
    public $user_id;

    public $layout;

    public $cart_quality = array();

    protected $listeners = ['cartUpdated' => 'cartUpdateView'];

    public function mount($layout = 1)
    {
        if (Auth::check()) {
            $this->user_col = 'user_id';
            $this->user_id = Auth::id();
        } else {
            $this->user_col = 'temp_user_id';
            $this->user_id = getTempUserId();
        }

        $this->layout = $layout;

        $this->carts = Cart::where($this->user_col, $this->user_id)->with('product')->get();
    }

    public function remove($id)
    {
        Cart::where([
            'id' => $id,
            $this->user_col => $this->user_id,
        ])->delete();

        Cache::forget('user_cart_count_' . $this->user_id);

        $this->dispatchBrowserEvent('updateCartCount', [
            'count' => cartCount()
        ]);

        $this->cartUpdateView();
    }

    public function increment($id)
    {
        $product_id = $this->cart_quality[$id]['id'];
        $this->cart_quality[$id]['qty'] += 1;
    }

    public function decrement($id)
    {
        $product_id = $this->cart_quality[$id]['id'];
        $this->cart_quality[$id]['qty'] -= 1;
    }

    public function render()
    {
        foreach ($this->carts as $cart) {
            array_push($this->cart_quality, [
                "id" => $cart->id,
                "qty" => $cart->quantity,
            ]);
        }

        if ($this->layout == 2) {
            return view('livewire.frontend.mini-cart-mobile');
        }

        return view('livewire.frontend.mini-cart');
    }

    public function cartUpdateView()
    {
        $this->carts = Cart::where($this->user_col, $this->user_id)->with('product')->get();
        $this->render();
    }
}
