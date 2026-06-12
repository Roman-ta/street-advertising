<?php

namespace App\Livewire\Public;

use App\Models\Spot;
use Livewire\Component;
use Livewire\WithPagination;

class SpotCatalog extends Component
{
    use WithPagination;

    // Фильтры
    public string $search   = '';
    public string $type     = '';
    public string $city     = '';
    public string $traffic  = '';
    public int    $price_min = 0;
    public int    $price_max = 10000;
    public bool   $lighting  = false;

    // Сброс пагинации при изменении фильтров
    public function updatingSearch():   void { $this->resetPage(); }
    public function updatingType():     void { $this->resetPage(); }
    public function updatingCity():     void { $this->resetPage(); }
    public function updatingTraffic():  void { $this->resetPage(); }
    public function updatingPriceMax(): void { $this->resetPage(); }
    public function updatingLighting(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'type', 'city', 'traffic', 'lighting']);
        $this->price_min = 0;
        $this->price_max = 10000;
        $this->resetPage();
    }

    public function render()
    {
        $spots = Spot::query()
            ->where('status', 'active')
            ->with(['mainPhoto', 'partner'])
            ->when($this->search, fn($q) =>
            $q->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('address', 'like', '%'.$this->search.'%');
            })
            )
            ->when($this->type,    fn($q) => $q->where('type', $this->type))
            ->when($this->city,    fn($q) => $q->where('city', $this->city))
            ->when($this->traffic, fn($q) => $q->where('traffic', $this->traffic))
            ->when($this->lighting, fn($q) => $q->where('lighting', true))
            ->whereBetween('price_month', [$this->price_min, $this->price_max])
            ->latest()
            ->paginate(12);

        return view('public.spot-catalog', compact('spots'));
    }
}
