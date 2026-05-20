<?php

namespace App\Livewire\Partner;

use App\Models\Spot;
use App\Models\SpotPhoto;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class SpotForm extends Component
{
    use WithFileUploads;

    // Если редактируем — сюда придёт id
    public ?int $spotId = null;

    // Поля формы
    public string $title       = '';
    public string $type        = 'billboard';
    public string $address     = '';
    public string $city        = 'Chisinau';
    public string $district    = '';
    public string $size_w      = '';
    public string $size_h      = '';
    public string $price_month = '';
    public string $description = '';
    public string $traffic     = 'medium';
    public bool   $lighting    = false;
    public array  $file_types_allowed = [];

    // Фото
    public array $photos = [];

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|min:3|max:200',
            'type'         => 'required|in:billboard,lightbox,led_screen,banner,transport,indoor,digital,event',
            'address'      => 'required|string|min:5',
            'city'         => 'required|string',
            'price_month'  => 'required|numeric|min:1',
            'size_w'       => 'nullable|numeric',
            'size_h'       => 'nullable|numeric',
            'description'  => 'nullable|string|max:2000',
            'traffic'      => 'required|in:low,medium,high',
            'photos.*'     => 'nullable|image|max:5120', // 5MB каждое
        ];
    }

    protected array $messages = [
        'title.required'       => 'Введите название площадки',
        'type.required'        => 'Выберите тип рекламы',
        'address.required'     => 'Введите адрес',
        'price_month.required' => 'Введите цену',
        'price_month.numeric'  => 'Цена должна быть числом',
        'photos.*.image'       => 'Файл должен быть изображением',
        'photos.*.max'         => 'Максимальный размер фото — 5MB',
    ];

    // Если редактируем — загружаем данные
    public function mount(?int $spotId = null): void
    {
        if ($spotId) {
            $spot = Spot::where('id', $spotId)
                ->where('partner_id', Auth::id())
                ->firstOrFail();

            $this->spotId            = $spot->id;
            $this->title             = $spot->title;
            $this->type              = $spot->type;
            $this->address           = $spot->address;
            $this->city              = $spot->city;
            $this->district          = $spot->district ?? '';
            $this->size_w            = $spot->size_w ?? '';
            $this->size_h            = $spot->size_h ?? '';
            $this->price_month       = $spot->price_month;
            $this->description       = $spot->description ?? '';
            $this->traffic           = $spot->traffic;
            $this->lighting          = $spot->lighting;
            $this->file_types_allowed = $spot->file_types_allowed ?? [];
        }
    }

    public function submit(): void
    {
        $this->validate();

        $data = [
            'partner_id'   => Auth::id(),
            'title'        => $this->title,
            'type'         => $this->type,
            'address'      => $this->address,
            'city'         => $this->city,
            'district'     => $this->district,
            'size_w'       => $this->size_w ?: null,
            'size_h'       => $this->size_h ?: null,
            'price_month'  => $this->price_month,
            'description'  => $this->description,
            'traffic'      => $this->traffic,
            'lighting'     => $this->lighting,
            'file_types_allowed' => $this->file_types_allowed,
            'status'       => 'moderation', // всегда на модерацию
        ];

        if ($this->spotId) {
            // Редактирование
            $spot = Spot::where('id', $this->spotId)
                ->where('partner_id', Auth::id())
                ->firstOrFail();
            $spot->update($data);
        } else {
            // Создание
            $spot = Spot::create($data);
        }

        // Сохраняем фото
        if (!empty($this->photos)) {
            foreach ($this->photos as $index => $photo) {
                $path = $photo->store('spots', 'public');

                SpotPhoto::create([
                    'spot_id'    => $spot->id,
                    'path'       => $path,
                    'sort_order' => $index,
                    'is_main'    => $index === 0, // первое фото — главное
                ]);
            }
        }

        session()->flash('success', $this->spotId
            ? 'Площадка обновлена и отправлена на модерацию'
            : 'Площадка создана и отправлена на модерацию'
        );

        $this->redirect(route('partner.spots'));
    }

    public function render()
    {
        return view('partner.spot-form');
    }
}
