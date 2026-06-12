<?php

namespace App\Livewire\Partner;

use App\Models\OrderFile;
use App\Models\OrderItem;
use App\Models\Spot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderShow extends Component
{
    use WithFileUploads;

    public OrderItem $item;
    public array $photos = [];
    public ?string $successMessage = null;
    public ?string $error = null;

    public function mount(int $id): void
    {
        $spotIds = Spot::where('partner_id', Auth::id())->pluck('id');

        $this->item = OrderItem::whereIn('spot_id', $spotIds)
            ->where('id', $id)
            ->with(['order.client', 'spot.mainPhoto', 'order.files'])
            ->firstOrFail();
    }

    public function uploadPhotoReport(): void
    {
        $this->validate([
            'photos.*' => 'required|image|max:10240',
        ]);

        if (empty($this->photos)) {
            $this->error = 'Выберите хотя бы одно фото';
            return;
        }

        foreach ($this->photos as $photo) {
            $path = $photo->store('photo-reports/' . $this->item->order_id, 'public');

            OrderFile::create([
                'order_id'    => $this->item->order_id,
                'uploader_id' => Auth::id(),
                'type'        => 'photo_report',
                'path'        => $path,
                'mime_type'   => $photo->getMimeType(),
                'size_bytes'  => $photo->getSize(),
            ]);
        }

        // КРИТИЧНО: запускаем таймер в момент загрузки фото
        $now = Carbon::now();
        $days = Carbon::parse($this->item->date_from)
                ->diffInDays(Carbon::parse($this->item->date_to)) + 1;

        $this->item->update([
            'placement_started_at' => $now,
            'placement_ends_at'    => $now->copy()->addDays($days),
        ]);

        // Меняем статус заказа
        $this->item->order->update(['status' => 'active']);

        $this->item->refresh();
        $this->photos = [];
        $this->successMessage = 'Фотоотчёт загружен! Таймер аренды запущен.';
    }

    public function render()
    {
        return view('partner.order-show');
    }
}
