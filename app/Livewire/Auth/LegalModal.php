<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LegalModal extends Component
{
    public bool $show    = false;
    public bool $checked = false;

    public function mount(): void
    {
        if (Auth::check() && !Auth::user()->legal_signed) {
            $this->show = true;
        }
    }

    public function accept(): void
    {
        if (!$this->checked) return;

        Auth::user()->update([
            'legal_signed'    => true,
            'legal_signed_at' => now(),
        ]);

        $this->show = false;
    }

    public function render()
    {
        return view('auth.legal-modal');
    }
}
