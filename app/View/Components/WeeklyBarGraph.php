<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WeeklyBarGraph extends Component
{
    public $data;

    /**
     * Create a new component instance.
     *
     * @param array|null $data
     * @return void
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.weekly-bar-graph', ['data' => $this->data]);
    }
}
