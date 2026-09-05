<?php

namespace App\Livewire\Admin\Account;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Accounts'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.account.index');
    }
}
