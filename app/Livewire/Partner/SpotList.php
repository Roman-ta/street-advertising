<?php

namespace App\Livewire\Partner;

use App\Models\Spot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotList extends Component
{
    public function delete(int $id): void
    {
        $spot = Spot::where('id', $id)
            ->where('partner_id', Auth::id())
            ->firstOrFail();

        // Удаляем фото
        foreach ($spot->photos as $photo) {
            if (file_exists(storage_path('app/public/' . $photo->path))) {
                unlink(storage_path('app/public/' . $photo->path));
            }
            $photo->delete();
        }

        $spot->delete();

        session()->flash('success', 'Площадка удалена');
    }

    public function render()
    {
        $spots = Spot::where('partner_id', Auth::id())
            ->with('photos')
            ->latest()
            ->get();

        return view('partner.spot-list', compact('spots'));
    }
}
