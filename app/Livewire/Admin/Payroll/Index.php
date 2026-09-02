<?php

namespace App\Livewire\Admin\Payroll;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollRun;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('layouts.app', ['title' => 'Payrolls'])]
class Index extends Component
{

    public function render()
    {
        return view('livewire.admin.payroll.index');
    }
}
