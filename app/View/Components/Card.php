<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\School;

class Card extends Component
{
    public $item;
    public $height;
    public $school;

    public function __construct($item, $height = '300px')
    {
        $this->item = $item;
        $this->height = $height;

    }

    public function render()
    {
        return view('components.card');
    }
}
