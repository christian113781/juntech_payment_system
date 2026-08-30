<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Products'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.product.index');
    }
}
