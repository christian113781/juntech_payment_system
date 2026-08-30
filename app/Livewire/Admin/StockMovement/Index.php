<?php

namespace App\Livewire\Admin\StockMovement;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Stock Movements'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.stock-movement.index');
    }
}
