<?php

namespace App\Livewire;

use Livewire\Component;

class ShowQrCode extends Component
{
        public $imgPath;

    public function mount($imgPath = null)
    {
        $this->imgPath = $imgPath;
    }


    public function render()
    {
        return view('livewire.show-qr-code');
    }
}
