<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Categories'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.category.index');
    }
}
