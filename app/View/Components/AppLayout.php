<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Bileşeni temsil eden görünümü / içeriği al.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
