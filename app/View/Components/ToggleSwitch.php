<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleSwitch extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct($model, $field, $id, $value)
    {
        $this->$model = "\App\Models\\".$model;
        $this->$field = $field;
        $this->$id = $id;
        $this->$value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.toggle-switch');
    }
}
