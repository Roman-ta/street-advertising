<div class="spot-form">

    <div class="spot-form__header">
        <h2>{{ $spotId ? 'Редактировать площадку' : 'Добавить площадку' }}</h2>
        <p>{{ $spotId ? 'Внесите изменения и сохраните' : 'Заполните информацию о вашей рекламной площадке' }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <form wire:submit="submit">

        {{-- Основная информация --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Основная информация</div>

            <div class="form__group">
                <label class="form__label">Тип рекламы *</label>
                <select wire:model="type" class="form__select">
                    <option value="billboard">📋 Билборд</option>
                    <option value="lightbox">💡 Лайтбокс</option>
                    <option value="led_screen">📺 LED экран</option>
                    <option value="banner">🏷 Баннер на фасаде</option>
                    <option value="transport">🚌 Реклама в транспорте</option>
                    <option value="indoor">🏢 Внутри помещений</option>
                    <option value="digital">📱 Digital/Media</option>
                    <option value="event">🎪 Event</option>
                </select>
                @error('type') <span class="form__error">{{ $message }}</span> @enderror
            </div>

            <div class="form__group">
                <label class="form__label">Название площадки *</label>
                <input
                    type="text"
                    wire:model="title"
                    placeholder="Например: Билборд 6×3м, центр Кишинёва"
                    class="form__input"
                >
                @error('title') <span class="form__error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Расположение --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Расположение</div>

            <div class="form__group">
                <label class="form__label">Адрес *</label>
                <input
                    type="text"
                    wire:model="address"
                    placeholder="Str. Ștefan cel Mare 1, Chișinău"
                    class="form__input"
                >
                @error('address') <span class="form__error">{{ $message }}</span> @enderror
            </div>

            <div class="form__row">
                <div class="form__group">
                    <label class="form__label">Город *</label>
                    <select wire:model="city" class="form__select">
                        <option value="Chisinau">Кишинёв</option>
                        <option value="Balti">Бельцы</option>
                        <option value="Cahul">Кагул</option>
                        <option value="Ungheni">Унгены</option>
                        <option value="Soroca">Сорока</option>
                        <option value="Orhei">Орхей</option>
                        <option value="Other">Другой</option>
                    </select>
                </div>
                <div class="form__group">
                    <label class="form__label">Район</label>
                    <input
                        type="text"
                        wire:model="district"
                        placeholder="Центр, Ботаника..."
                        class="form__input"
                    >
                </div>
            </div>

            {{-- Карта --}}
            <div class="form__group">
                <label class="form__label">Укажите на карте</label>
                <div id="spot-map" style="height:280px; border-radius:8px; border:1px solid #e5e7eb; overflow:hidden"></div>
                <p style="font-size:12px; color:#9ca3af; margin-top:6px">
                    Кликните на карту чтобы указать точное расположение
                </p>
                <input type="hidden" wire:model="lat" id="spot-lat">
                <input type="hidden" wire:model="lng" id="spot-lng">
            </div>
        </div>

        {{-- Характеристики --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Характеристики</div>

            <div class="form__row">
                <div class="form__group">
                    <label class="form__label">Ширина (м)</label>
                    <input type="number" wire:model="size_w" placeholder="6" step="0.1" class="form__input">
                </div>
                <div class="form__group">
                    <label class="form__label">Высота (м)</label>
                    <input type="number" wire:model="size_h" placeholder="3" step="0.1" class="form__input">
                </div>
            </div>

            <div class="form__row">
                <div class="form__group">
                    <label class="form__label">Трафик</label>
                    <select wire:model="traffic" class="form__select">
                        <option value="low">🟢 Низкий</option>
                        <option value="medium">🟡 Средний</option>
                        <option value="high">🔴 Высокий</option>
                    </select>
                </div>
                <div class="form__group">
                    <label class="form__label">Подсветка</label>
                    <label class="form__checkbox" style="margin-top:12px; font-size:15px">
                        <input type="checkbox" wire:model="lighting">
                        <span>Есть подсветка ☀️</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Цена --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Стоимость</div>

            <div class="form__group">
                <label class="form__label">Цена в месяц ($) *</label>
                <div style="position:relative">
                    <span style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-weight:600">$</span>
                    <input
                        type="number"
                        wire:model="price_month"
                        placeholder="1500"
                        class="form__input"
                        style="padding-left:28px"
                    >
                </div>
                @error('price_month') <span class="form__error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Описание --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Описание</div>

            <div class="form__group">
                <textarea
                    wire:model="description"
                    rows="4"
                    placeholder="Опишите особенности площадки..."
                    class="form__textarea"
                ></textarea>
            </div>
        </div>

        {{-- Форматы файлов --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Принимаемые форматы материалов</div>

            <div class="spot-form__file-types">
                @foreach(['pdf' => 'PDF', 'png' => 'PNG', 'jpg' => 'JPG', 'tiff' => 'TIFF', 'mp4' => 'MP4', 'ai' => 'AI'] as $value => $label)
                    <label>
                        <input type="checkbox" wire:model="file_types_allowed" value="{{ $value }}">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Фото --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Фотографии площадки</div>

            <label class="spot-form__upload-area" onclick="document.getElementById('photos-input').click()">
                <input
                    id="photos-input"
                    type="file"
                    wire:model="photos"
                    multiple
                    accept="image/*"
                    style="display:none"
                >
                <div style="font-size:40px; margin-bottom:8px">📷</div>
                <strong style="font-size:15px; color:#374151">Нажмите чтобы выбрать фото</strong>
                <p>До 10 фотографий · максимум 5MB каждое · JPG, PNG, WebP</p>
            </label>

            <div wire:loading wire:target="photos" style="text-align:center; padding:12px; color:#5B21B6; font-size:14px">
                ⏳ Загружаем фото...
            </div>

            @error('photos.*') <span class="form__error">{{ $message }}</span> @enderror

            @if(!empty($photos))
                <div class="spot-form__photos-preview">
                    @foreach($photos as $index => $photo)
                        <div style="position:relative; display:inline-block">
                            <img src="{{ $photo->temporaryUrl() }}" alt="preview">
                            @if($index === 0)
                                <span style="position:absolute; bottom:4px; left:4px; background:rgba(91,33,182,0.9); color:white; font-size:10px; padding:2px 6px; border-radius:4px;">
                                    Главное
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Кнопки --}}
        <div class="spot-form__actions">
            <button type="submit" class="btn btn--primary btn--lg">
                <span wire:loading.remove>
                    {{ $spotId ? '💾 Сохранить изменения' : '🚀 Отправить на модерацию' }}
                </span>
                <span wire:loading>Сохраняем...</span>
            </button>
            <a href="{{ route('partner.spots') }}" class="btn btn--outline btn--lg">Отмена</a>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('spot-map')) return;

            const map = L.map('spot-map').setView([47.0245, 28.8322], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = null;

            const lat = document.getElementById('spot-lat').value;
            const lng = document.getElementById('spot-lng').value;
            if (lat && lng) {
                marker = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map);
                map.setView([parseFloat(lat), parseFloat(lng)], 15);
            }

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);

            @this.set('lat', lat.toString());
            @this.set('lng', lng.toString());

                fetch(`https://map.md/api/companies/webmap/near?lat=${lat}&lon=${lng}`)
                    .then(r => r.json())
                    .then(data => {
                        const street = data.find(p => p.type === 'street');
                        if (street) @this.set('address', street.name);
                    })
                    .catch(() => {});
            });
        });
    </script>
</div>
