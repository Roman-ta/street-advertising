<?php

namespace App\Livewire\Public;

use App\Models\Spot;
use App\Models\SpotAvailability;
use Carbon\Carbon;
use Livewire\Component;

class SpotShow extends Component
{
    public Spot $spot;

    // Выбранные даты
    public ?string $date_from = null;
    public ?string $date_to   = null;

    // Занятые даты (массив строк 'Y-m-d')
    public array $occupiedDates = [];

    // Расчёт
    public int   $days        = 0;
    public float $base_price  = 0;
    public float $commission  = 0;
    public float $total       = 0;

    public ?string $error = null;

    public function mount(int $id): void
    {
        $this->spot = Spot::where('id', $id)
            ->where('status', 'active')
            ->with(['photos', 'partner', 'availabilities'])
            ->firstOrFail();

        $this->loadOccupiedDates();
    }

    private function loadOccupiedDates(): void
    {
        $this->occupiedDates = [];

        foreach ($this->spot->availabilities as $availability) {
            $start = Carbon::parse($availability->date_from);
            $end   = Carbon::parse($availability->date_to);

            while ($start->lte($end)) {
                $this->occupiedDates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }
    }

    public function updatedDateFrom(): void
    {
        $this->error = null;
        $this->calculatePrice();
    }

    public function updatedDateTo(): void
    {
        $this->error = null;
        $this->calculatePrice();
    }

    private function calculatePrice(): void
    {
        if (!$this->date_from || !$this->date_to) {
            $this->days = $this->base_price = $this->commission = $this->total = 0;
            return;
        }

        $from = Carbon::parse($this->date_from);
        $to   = Carbon::parse($this->date_to);

        if ($to->lte($from)) {
            $this->error = 'Дата окончания должна быть позже даты начала';
            return;
        }

        // Проверяем нет ли занятых дней в диапазоне
        $current = $from->copy();
        while ($current->lte($to)) {
            if (in_array($current->format('Y-m-d'), $this->occupiedDates)) {
                $this->error = 'В выбранном периоде есть занятые даты';
                $this->days = $this->base_price = $this->commission = $this->total = 0;
                return;
            }
            $current->addDay();
        }

        $this->days       = $from->diffInDays($to) + 1;
        $pricePerDay      = $this->spot->price_month / 30;
        $this->base_price = round($pricePerDay * $this->days, 2);
        $this->commission = round($this->base_price * 0.10, 2);
        $this->total      = round($this->base_price + $this->commission, 2);
    }

    public function addToCart(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        if (!$this->date_from || !$this->date_to || $this->error) {
            $this->error = 'Выберите корректный период';
            return;
        }

        // Сохраняем в сессию (корзина)
        $cart = session()->get('cart', []);

        $key = $this->spot->id . '_' . $this->date_from . '_' . $this->date_to;

        $cart[$key] = [
            'spot_id'    => $this->spot->id,
            'spot_title' => $this->spot->title,
            'spot_type'  => $this->spot->type,
            'address'    => $this->spot->address,
            'date_from'  => $this->date_from,
            'date_to'    => $this->date_to,
            'days'       => $this->days,
            'base_price' => $this->base_price,
            'commission' => $this->commission,
            'total'      => $this->total,
            'photo'      => $this->spot->mainPhoto?->path,
        ];

        session()->put('cart', $cart);

        $this->redirect(route('cart'));
    }

    public function render()
    {
        return view('public.spot-show');
    }
}
