<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class TempUpload extends Component
{
    use WithFileUploads;

    public $files = [];

    public function render()
    {
        return view('livewire.temp-upload')->extends('backend.layouts.app');
    }

    public function updatedFiles()
    {
        dd($this->files);
        // You can do whatever you want to do with $this->files here
    }
}
