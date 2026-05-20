<div>
    <div style="max-width:700px; margin:0 auto">

        <h2>{{ $spotId ? 'Редактировать площадку' : 'Добавить площадку' }}</h2>

        @if(session('success'))
            <div style="background:#D1FAE5; color:#065F46; padding:12px 16px; border-radius:8px; margin-bottom:16px">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="submit">

            {{-- Тип рекламы --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">Тип рекламы *</label>
                <select wire:model="type" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px">
                    <option value="billboard">Билборд</option>
                    <option value="lightbox">Лайтбокс</option>
                    <option value="led_screen">LED экран</option>
                    <option value="banner">Баннер на фасаде</option>
                    <option value="transport">Реклама в транспорте</option>
                    <option value="indoor">Внутри помещений</option>
                    <option value="digital">Digital/Media</option>
                    <option value="event">Event</option>
                </select>
                @error('type') <span style="color:red; font-size:13px">{{ $message }}</span> @enderror
            </div>

            {{-- Название --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">Название *</label>
                <input
                    type="text"
                    wire:model="title"
                    placeholder="Например: Билборд 6×3м, ул. Штефан чел Маре"
                    style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                >
                @error('title') <span style="color:red; font-size:13px">{{ $message }}</span> @enderror
            </div>

            {{-- Адрес --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">Адрес *</label>
                <input
                    type="text"
                    wire:model="address"
                    placeholder="Str. Ștefan cel Mare 1, Chișinău"
                    style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                >
                @error('address') <span style="color:red; font-size:13px">{{ $message }}</span> @enderror
            </div>

            {{-- Город и район --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Город *</label>
                    <select wire:model="city" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px">
                        <option value="Chisinau">Кишинёв</option>
                        <option value="Balti">Бельцы</option>
                        <option value="Cahul">Кагул</option>
                        <option value="Ungheni">Унгены</option>
                        <option value="Soroca">Сорока</option>
                        <option value="Orhei">Орхей</option>
                        <option value="Other">Другой</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Район</label>
                    <input
                        type="text"
                        wire:model="district"
                        placeholder="Центр, Ботаника..."
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                    >
                </div>
            </div>

            {{-- Размер --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Ширина (м)</label>
                    <input
                        type="number"
                        wire:model="size_w"
                        placeholder="6"
                        step="0.1"
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                    >
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Высота (м)</label>
                    <input
                        type="number"
                        wire:model="size_h"
                        placeholder="3"
                        step="0.1"
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                    >
                </div>
            </div>

            {{-- Цена --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">Цена в месяц ($) *</label>
                <input
                    type="number"
                    wire:model="price_month"
                    placeholder="1500"
                    style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                >
                @error('price_month') <span style="color:red; font-size:13px">{{ $message }}</span> @enderror
            </div>

            {{-- Трафик и подсветка --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Трафик</label>
                    <select wire:model="traffic" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px">
                        <option value="low">Низкий</option>
                        <option value="medium">Средний</option>
                        <option value="high">Высокий</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:8px">Подсветка</label>
                    <label style="display:flex; align-items:center; gap:8px; margin-top:12px; cursor:pointer">
                        <input type="checkbox" wire:model="lighting" style="width:18px; height:18px">
                        <span>Есть подсветка</span>
                    </label>
                </div>
            </div>

            {{-- Описание --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">Описание</label>
                <textarea
                    wire:model="description"
                    rows="4"
                    placeholder="Опишите особенности площадки, удобство расположения, целевую аудиторию..."
                    style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box; resize:vertical"
                ></textarea>
            </div>

            {{-- Допустимые форматы файлов --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-weight:600; margin-bottom:8px">
                    Принимаемые форматы рекламных материалов
                </label>
                <div style="display:flex; flex-wrap:wrap; gap:12px">
                    @foreach(['pdf' => 'PDF', 'png' => 'PNG', 'jpg' => 'JPG', 'tiff' => 'TIFF', 'mp4' => 'MP4', 'ai' => 'AI'] as $value => $label)
                        <label style="display:flex; align-items:center; gap:6px; cursor:pointer">
                            <input
                                type="checkbox"
                                wire:model="file_types_allowed"
                                value="{{ $value }}"
                            >
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Фото --}}
            <div style="margin-bottom:24px">
                <label style="display:block; font-weight:600; margin-bottom:8px">
                    Фотографии площадки (до 10 штук, max 5MB каждое)
                </label>
                <input
                    type="file"
                    wire:model="photos"
                    multiple
                    accept="image/*"
                    style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; box-sizing:border-box"
                >
                @error('photos.*') <span style="color:red; font-size:13px">{{ $message }}</span> @enderror

                {{-- Превью загружаемых фото --}}
                @if(!empty($photos))
                    <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap">
                        @foreach($photos as $photo)
                            <img
                                src="{{ $photo->temporaryUrl() }}"
                                style="width:80px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #e5e7eb"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Кнопки --}}
            <div style="display:flex; gap:12px">
                <button type="submit" style="
                    background:#5B21B6; color:white;
                    padding:12px 32px; border:none;
                    border-radius:8px; font-size:16px;
                    cursor:pointer;
                ">
                    <span wire:loading.remove>
                        {{ $spotId ? 'Сохранить изменения' : 'Отправить на модерацию' }}
                    </span>
                    <span wire:loading>Сохраняем...</span>
                </button>

                <a href="{{ route('partner.spots') }}" style="
                    padding:12px 24px; border:1px solid #e5e7eb;
                    border-radius:8px; text-decoration:none;
                    color:#374151; font-size:16px;
                ">Отмена</a>
            </div>

        </form>
    </div>
</div>
