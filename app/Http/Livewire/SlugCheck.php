<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class SlugCheck extends Component
{
    public $slug = "";
    public $required = false;
    public $model = "";
    public $model_id = "";
    public $template = 1;
    protected $listeners = ['titleChanged' => 'generateUniqueSlug'];


    public function mount($model, $model_id = "", $required = true, $template = 1)
    {
        $this->model = $model;
        $this->model_id = $model_id;
        $this->required = $required;
        $this->template = $template;

        if ($model_id) {
            $curr_model = $this->model::where('id', '=', $model_id)->pluck('slug')->first();
            $this->slug = $curr_model;
        }
    }

    public function render()
    {
        return view('livewire.slug-check');
    }

    public function isUnique()
    {
        $this->generateUniqueSlug($this->slug);
    }

    public function generateUniqueSlug($value)
    {
        $slug = Str::of($value)->slug('-');

        if ($this->model_id !== "") {
            if ($this->model::where('id', '!=', $this->model_id)->where('slug', '=', $slug)->count() > 0) {
                // dd($this->model::where('id', '!=', $this->model_id)->where('slug', '=', $slug)->get());
                $slug = $this->incrementSlug($slug);
            }
        } else {
            if ($this->model::where('slug', '=', $slug)) {
                $slug = $this->incrementSlug($slug);
            }
        }
        $this->slug = $slug;
    }

    public function incrementSlug($slug)
    {
        $original = $slug;
        $count = 2;
        while ($this->model::where('slug', '=', $slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }
        return $slug;
    }
}
