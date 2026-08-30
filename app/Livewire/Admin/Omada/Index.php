<?php

namespace App\Livewire\Admin\Omada;
use Livewire\Attributes\Layout;

use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Omada Partner'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.omada.index');
    }
}
