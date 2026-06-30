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
    public $spotTypes;
    public string $min_rental_days = '7';
    public array $existingPhotos = []; // фото уже сохранённые в БД (для отображения/удаления)
    public string $lat = '';
    public string $lng = '';

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
            'min_rental_days' => 'required|integer|min:7|max:365',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
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
        $this->spotTypes = \App\Models\SpotType::where('is_active', true)->orderBy('sort_order')->get();
        if ($spotId) {
            $spot = Spot::where('id', $spotId)
                ->where('partner_id', Auth::id())
                ->with('photos')
                ->firstOrFail();

            $this->spotId             = $spot->id;
            $this->title               = $spot->title;
            $this->type                = $spot->type;
            $this->address              = $spot->address;
            $this->city                 = $spot->city;
            $this->district             = $spot->district ?? '';
            $this->size_w               = $spot->size_w ?? '';
            $this->size_h               = $spot->size_h ?? '';
            $this->price_month          = $spot->price_month;
            $this->description          = $spot->description ?? '';
            $this->traffic               = $spot->traffic;
            $this->lighting              = $spot->lighting;
            $this->file_types_allowed    = $spot->file_types_allowed ?? [];
            $this->lat                  = $spot->lat ?? '';
            $this->lng                  = $spot->lng ?? '';
            $this->min_rental_days = (string) ($spot->min_rental_days ?? 7);

            // Загружаем существующие фото для отображения
            $this->existingPhotos = $spot->photos->map(fn($p) => [
                'id'      => $p->id,
                'path'    => $p->path,
                'is_main' => $p->is_main,
            ])->toArray();
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
            'min_rental_days' => $this->min_rental_days,
            'lat' => $this->lat ?: null,
            'lng' => $this->lng ?: null,
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
            $hasMainAlready = $spot->photos()->where('is_main', true)->exists();

            foreach ($this->photos as $index => $photo) {
                $path = $photo->store('spots', 'public');

                SpotPhoto::create([
                    'spot_id'    => $spot->id,
                    'path'       => $path,
                    'sort_order' => $index,
                    'is_main'    => !$hasMainAlready && $index === 0, // главное только если ещё нет главного
                ]);
            }
        }

        session()->flash('success', $this->spotId
            ? 'Площадка обновлена и отправлена на модерацию'
            : 'Площадка создана и отправлена на модерацию'
        );

        $this->redirect(route('partner.spots'));
    }
    public function deletePhoto(int $photoId): void
    {
        $photo = SpotPhoto::where('id', $photoId)
            ->whereHas('spot', fn($q) => $q->where('partner_id', Auth::id()))
            ->firstOrFail();

        // Удаляем файл с диска
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
        }

        $wasMain = $photo->is_main;
        $spotId  = $photo->spot_id;

        $photo->delete();

        // Если удалили главное фото — назначаем главным следующее по порядку
        if ($wasMain) {
            $nextPhoto = SpotPhoto::where('spot_id', $spotId)->orderBy('sort_order')->first();
            if ($nextPhoto) {
                $nextPhoto->update(['is_main' => true]);
            }
        }

        // Обновляем список в компоненте без перезагрузки страницы
        $this->existingPhotos = collect($this->existingPhotos)
            ->reject(fn($p) => $p['id'] === $photoId)
            ->values()
            ->toArray();

        session()->flash('success', 'Фото удалено');
    }

    public function setMainPhoto(int $photoId): void
    {
        $photo = SpotPhoto::where('id', $photoId)
            ->whereHas('spot', fn($q) => $q->where('partner_id', Auth::id()))
            ->firstOrFail();

        // Снимаем главное со всех фото этой площадки
        SpotPhoto::where('spot_id', $photo->spot_id)->update(['is_main' => false]);
        $photo->update(['is_main' => true]);

        // Обновляем локальный массив
        $this->existingPhotos = collect($this->existingPhotos)
            ->map(function($p) use ($photoId) {
                $p['is_main'] = $p['id'] === $photoId;
                return $p;
            })
            ->toArray();
    }

    public function render()
    {
        return view('partner.spot-form');
    }
}
