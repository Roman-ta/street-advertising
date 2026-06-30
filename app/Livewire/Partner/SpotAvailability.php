<?php

namespace App\Livewire\Partner;

use App\Models\Spot;
use App\Models\SpotAvailability as SpotAvailabilityModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SpotAvailability extends Component
{
    public Spot $spot;
    public ?string $date_from = null;
    public ?string $date_to = null;
    public ?string $error = null;
    public ?string $success = null;

    public function mount(int $spotId): void
    {
        $this->spot = Spot::where('id', $spotId)
            ->where('partner_id', Auth::id())
            ->with(['availabilities' => fn($q) => $q->orderBy('date_from', 'desc')])
            ->firstOrFail();
    }

    public function blockDates(): void
    {
        $this->error = null;
        $this->success = null;

        $this->validate([
            'date_from' => 'required|date|after_or_equal:today',
            'date_to'   => 'required|date|after_or_equal:date_from',
        ]);

        // Проверка пересечения с уже занятыми датами (включая брони клиентов)
        $conflict = SpotAvailabilityModel::where('spot_id', $this->spot->id)
            ->where(function($q) {
                $q->whereBetween('date_from', [$this->date_from, $this->date_to])
                    ->orWhereBetween('date_to', [$this->date_from, $this->date_to])
                    ->orWhere(function($q) {
                        $q->where('date_from', '<=', $this->date_from)
                            ->where('date_to', '>=', $this->date_to);
                    });
            })
            ->exists();

        if ($conflict) {
            $this->error = 'В выбранном периоде уже есть бронь или блокировка';
            return;
        }

        SpotAvailabilityModel::create([
            'spot_id'   => $this->spot->id,
            'date_from' => $this->date_from,
            'date_to'   => $this->date_to,
            'status'    => 'manual_block',
        ]);

        $this->spot->refresh();
        $this->spot->load('availabilities');

        $this->reset(['date_from', 'date_to']);
        $this->success = 'Даты заблокированы';
    }

    public function unblockDates(int $availabilityId): void
    {
        $availability = SpotAvailabilityModel::where('id', $availabilityId)
            ->where('spot_id', $this->spot->id)
            ->where('status', 'manual_block') // нельзя снять блокировку с реальной брони клиента
            ->firstOrFail();

        $availability->delete();

        $this->spot->refresh();
        $this->spot->load('availabilities');

        $this->success = 'Блокировка снята';
    }

    public function render()
    {
        return view('partner.spot-availability');
    }
}
