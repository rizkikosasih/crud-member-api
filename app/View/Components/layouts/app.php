<?php

namespace App\View\Components\layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class app extends Component
{
    public $title;
    public $hasSidebar;

    public function __construct($title = null, $hasSidebar = true)
    {
        $this->title = $title;
        $this->hasSidebar = $hasSidebar;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.app');
    }
}
