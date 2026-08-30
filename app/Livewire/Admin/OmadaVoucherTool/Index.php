<?php

namespace App\Livewire\Admin\OmadaVoucherTool;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Omada Voucher Designer'])]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.omada-voucher-tool.index');
    }
}
