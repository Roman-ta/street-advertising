<?php

namespace App\Livewire\Client;

use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderShow extends Component
{
    use WithFileUploads;

    public Order $order;
    public array $uploadedFiles = [];
    public ?string $successMessage = null;

    public function mount(int $id): void
    {
        $this->order = Order::where('id', $id)
            ->where('client_id', Auth::id())
            ->with(['items.spot.mainPhoto', 'files'])
            ->firstOrFail();
    }

    // Симуляция оплаты (заглушка — до реальной платёжки)
    public function simulatePayment(): void
    {
        $this->order->update(['status' => 'paid_pending']);
        $this->order->refresh();
        $this->successMessage = 'Оплата прошла успешно! Загрузите рекламные материалы.';
    }

    public function uploadMaterials(): void
    {
        $this->validate([
            'uploadedFiles.*' => 'required|file|max:512000', // 500MB
        ]);

        foreach ($this->uploadedFiles as $file) {
            $path = $file->store('order-materials/' . $this->order->id, 'public');

            OrderFile::create([
                'order_id'    => $this->order->id,
                'uploader_id' => Auth::id(),
                'type'        => 'material',
                'path'        => $path,
                'mime_type'   => $file->getMimeType(),
                'size_bytes'  => $file->getSize(),
            ]);
        }

        $this->order->update(['status' => 'materials_ready']);
        $this->order->refresh();
        $this->uploadedFiles = [];
        $this->successMessage = 'Материалы успешно загружены! Партнёр получит уведомление.';
    }

    public function render()
    {
        return view('client.order-show');
    }
}
