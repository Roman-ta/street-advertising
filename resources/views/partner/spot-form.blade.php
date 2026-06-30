<div class="spot-form">

    <div class="spot-form__header">
        <h2>{{ $spotId ? __('messages.spot_form.edit_title') : __('messages.spot_form.add_title') }}</h2>
        <p>{{ $spotId ? __('messages.spot_form.edit_subtitle') : __('messages.spot_form.add_subtitle') }}</p>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <form wire:submit="submit">

        {{-- Основная информация --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_main') }}</div>

            <div class="form__group">
                <label class="form__label">{{ __('messages.spot_form.type_label') }}</label>
                <select wire:model="type" class="form__select">
                    @foreach($spotTypes as $spotType)
                        <option value="{{ $spotType->slug }}">{{ $spotType->icon }} {{ $spotType->name }}</option>
                    @endforeach
                </select>
                @error('type') <span class="form__error">{{ $message }}</span> @enderror
            </div>

            <div class="form__group">
                <label class="form__label">{{ __('messages.spot_form.title_label') }}</label>
                <input
                    type="text"
                    wire:model="title"
                    placeholder="{{ __('messages.spot_form.title_placeholder') }}"
                    class="form__input"
                >
                @error('title') <span class="form__error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Расположение --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_location') }}</div>

            <div class="form__group">
                <label class="form__label">{{ __('messages.spot_form.address_label') }}</label>
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
                    <label class="form__label">{{ __('messages.spot_form.city_label') }}</label>
                    <select wire:model="city" class="form__select">
                        <option value="Chisinau">{{ __('messages.spot_form.city_chisinau') }}</option>
                        <option value="Balti">{{ __('messages.spot_form.city_balti') }}</option>
                        <option value="Cahul">{{ __('messages.spot_form.city_cahul') }}</option>
                        <option value="Ungheni">{{ __('messages.spot_form.city_ungheni') }}</option>
                        <option value="Soroca">{{ __('messages.spot_form.city_soroca') }}</option>
                        <option value="Orhei">{{ __('messages.spot_form.city_orhei') }}</option>
                        <option value="Other">{{ __('messages.spot_form.city_other') }}</option>
                    </select>
                </div>
                <div class="form__group">
                    <label class="form__label">{{ __('messages.spot_form.district_label') }}</label>
                    <input
                        type="text"
                        wire:model="district"
                        placeholder="{{ __('messages.spot_form.district_ph') }}"
                        class="form__input"
                    >
                </div>
            </div>

            {{-- Карта --}}
            <div class="form__group">
                <label class="form__label">{{ __('messages.spot_form.map_label') }}</label>
                <div wire:ignore>
                    <div id="spot-map" style="height:280px; border-radius:8px; border:1px solid #e5e7eb; overflow:hidden"></div>
                </div>
                <p style="font-size:12px; color:#9ca3af; margin-top:6px">
                    {{ __('messages.spot_form.map_hint') }}
                </p>
                <input type="hidden" wire:model="lat" id="spot-lat">
                <input type="hidden" wire:model="lng" id="spot-lng">
            </div>
        </div>

        {{-- Характеристики --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_specs') }}</div>

            <div class="form__row">
                <div class="form__group">
                    <label class="form__label">{{ __('messages.spot_form.width_label') }}</label>
                    <input type="number" wire:model="size_w" placeholder="6" step="0.1" class="form__input">
                </div>
                <div class="form__group">
                    <label class="form__label">{{ __('messages.spot_form.height_label') }}</label>
                    <input type="number" wire:model="size_h" placeholder="3" step="0.1" class="form__input">
                </div>
            </div>

            <div class="form__row">
                <div class="form__group">
                    <label class="form__label">{{ __('messages.spot_form.traffic_label') }}</label>
                    <select wire:model="traffic" class="form__select">
                        <option value="low">🟢 {{ __('messages.spot_form.traffic_low') }}</option>
                        <option value="medium">🟡 {{ __('messages.spot_form.traffic_medium') }}</option>
                        <option value="high">🔴 {{ __('messages.spot_form.traffic_high') }}</option>
                    </select>
                </div>
                <div class="form__group">
                    <label class="form__label">{{ __('messages.spot_form.lighting_label') }}</label>
                    <label class="form__checkbox" style="margin-top:12px; font-size:15px">
                        <input type="checkbox" wire:model="lighting">
                        <span>{{ __('messages.spot_form.lighting_check') }} ☀️</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Цена --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">Стоимость</div>

            <div class="form__group">
                <label class="form__label">Цена в месяц (lei) *</label>
                <div style="position:relative">
                    <input
                        type="number"
                        wire:model="price_month"
                        placeholder="1500"
                        class="form__input"
                    >
                </div>
                @error('price_month') <span class="form__error">{{ $message }}</span> @enderror
            </div>

            <div class="form__group">
                <label class="form__label">Минимальный срок аренды (дней) *</label>
                <input
                    type="number"
                    wire:model="min_rental_days"
                    min="1"
                    placeholder="1"
                    class="form__input"
                >
                <p style="font-size:12px; color:#9ca3af; margin-top:4px;">
                    Клиент не сможет арендовать площадку на меньший срок
                </p>
                @error('min_rental_days') <span class="form__error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Описание --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_desc') }}</div>

            <div class="form__group">
                <textarea
                    wire:model="description"
                    rows="4"
                    placeholder="{{ __('messages.spot_form.desc_placeholder') }}"
                    class="form__textarea"
                ></textarea>
            </div>
        </div>

        {{-- Форматы файлов --}}
        <div class="spot-form__section">
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_formats') }}</div>

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
            <div class="spot-form__section-title">{{ __('messages.spot_form.section_photos') }}</div>

            {{-- Текущие фото (только при редактировании) --}}
            @if(!empty($existingPhotos))
                <div style="margin-bottom:16px">
                    <p style="font-size:13px; color:#6b7280; margin-bottom:8px;">Текущие фото:</p>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        @foreach($existingPhotos as $photo)
                            <div style="position:relative; width:110px;">
                                <img
                                    src="{{ Storage::url($photo['path']) }}"
                                    style="width:110px; height:82px; object-fit:cover; border-radius:8px; border:2px solid {{ $photo['is_main'] ? '#5B21B6' : '#e5e7eb' }};"
                                >
                                @if($photo['is_main'])
                                    <span style="position:absolute; top:4px; left:4px; background:rgba(91,33,182,0.9); color:white; font-size:9px; padding:2px 6px; border-radius:4px;">
                                Главное
                            </span>
                                @endif

                                <div style="display:flex; gap:4px; margin-top:4px;">
                                    @if(!$photo['is_main'])
                                        <button
                                            type="button"
                                            wire:click="setMainPhoto({{ $photo['id'] }})"
                                            style="flex:1; font-size:10px; background:#f3f4f6; border:none; border-radius:4px; padding:4px; cursor:pointer; color:#374151;"
                                            title="Сделать главным"
                                        >★</button>
                                    @endif
                                    <button
                                        type="button"
                                        wire:click="deletePhoto({{ $photo['id'] }})"
                                        wire:confirm="Удалить это фото?"
                                        style="flex:1; font-size:10px; background:#FEE2E2; border:none; border-radius:4px; padding:4px; cursor:pointer; color:#991B1B;"
                                    >Удалить</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            {{-- Загрузка новых фото --}}
            <div class="spot-form__upload-area" onclick="document.getElementById('photos-input').click()" style="cursor:pointer;">
                <div style="font-size:40px; margin-bottom:8px;">📷</div>
                <strong style="font-size:15px; color:#374151; display:block; margin-bottom:6px;">{{ __('messages.spot_form.photo_click') }}</strong>
                <p style="margin:0; color:#9ca3af; font-size:13px;">{{ __('messages.spot_form.photo_hint') }}</p>
            </div>

            <input
                id="photos-input"
                type="file"
                wire:model="photos"
                multiple
                accept="image/*"
                style="display:none"
            >

            <div wire:loading wire:target="photos" style="text-align:center; padding:12px; color:#5B21B6; font-size:14px;">
                ⏳ {{ __('messages.spot_form.photo_loading') }}
            </div>

            @error('photos.*')
            <span class="form__error">{{ $message }}</span>
            @enderror

            @if(!empty($photos))
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:12px;">
                    @foreach($photos as $index => $photo)
                        <div style="position:relative; width:200px; height:150px;">
                            <img src="{{ $photo->temporaryUrl() }}" alt="preview" style="width:100%; height:100%; object-fit:cover; border-radius:6px; display:block;">
                            @if($index === 0)
                                <span style="position:absolute; bottom:4px; left:4px; background:rgba(91,33,182,0.9); color:white; font-size:10px; padding:2px 6px; border-radius:4px;">{{ __('messages.spot_form.photo_main') }}</span>
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
                    {{ $spotId ? '💾 ' . __('messages.spot_form.btn_save') : '🚀 ' . __('messages.spot_form.btn_submit') }}
                </span>
                <span wire:loading>{{ __('messages.spot_form.btn_saving') }}</span>
            </button>
            <a href="{{ route('partner.spots') }}" class="btn btn--outline btn--lg">{{ __('messages.spot_form.btn_cancel') }}</a>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('spot-map')) return;

            const map = L.map('spot-map').setView([47.0245, 28.8322], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Фикс: пересчитать размер карты после рендера
            setTimeout(() => map.invalidateSize(), 100);

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

                // Снова пересчитать размер на всякий случай
                setTimeout(() => map.invalidateSize(), 50);
            });
        });
    </script>
</div>
