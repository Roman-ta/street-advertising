<div class="spot-form">

    <h2 class="spot-form__title">
        {{ $spotId ? 'Редактировать площадку' : 'Добавить площадку' }}
    </h2>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <form wire:submit="submit">

        <div class="form__group">
            <label class="form__label">Тип рекламы *</label>
            <select wire:model="type" class="form__select">
                <option value="billboard">Билборд</option>
                <option value="lightbox">Лайтбокс</option>
                <option value="led_screen">LED экран</option>
                <option value="banner">Баннер на фасаде</option>
                <option value="transport">Реклама в транспорте</option>
                <option value="indoor">Внутри помещений</option>
                <option value="digital">Digital/Media</option>
                <option value="event">Event</option>
            </select>
            @error('type') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__group">
            <label class="form__label">Название *</label>
            <input
                type="text"
                wire:model="title"
                placeholder="Например: Билборд 6×3м, ул. Штефан чел Маре"
                class="form__input"
            >
            @error('title') <span class="form__error">{{ $message }}</span> @enderror
        </div>

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
                <input type="text" wire:model="district" placeholder="Центр, Ботаника..." class="form__input">
            </div>
        </div>

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

        <div class="form__group">
            <label class="form__label">Цена в месяц ($) *</label>
            <input type="number" wire:model="price_month" placeholder="1500" class="form__input">
            @error('price_month') <span class="form__error">{{ $message }}</span> @enderror
        </div>

        <div class="form__row">
            <div class="form__group">
                <label class="form__label">Трафик</label>
                <select wire:model="traffic" class="form__select">
                    <option value="low">Низкий</option>
                    <option value="medium">Средний</option>
                    <option value="high">Высокий</option>
                </select>
            </div>
            <div class="form__group">
                <label class="form__label">Подсветка</label>
                <label class="form__checkbox" style="margin-top:12px">
                    <input type="checkbox" wire:model="lighting">
                    <span>Есть подсветка</span>
                </label>
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">Описание</label>
            <textarea
                wire:model="description"
                rows="4"
                placeholder="Опишите особенности площадки..."
                class="form__textarea"
            ></textarea>
        </div>

        <div class="form__group">
            <label class="form__label">Принимаемые форматы рекламных материалов</label>
            <div class="spot-form__file-types">
                @foreach(['pdf' => 'PDF', 'png' => 'PNG', 'jpg' => 'JPG', 'tiff' => 'TIFF', 'mp4' => 'MP4', 'ai' => 'AI'] as $value => $label)
                    <label>
                        <input type="checkbox" wire:model="file_types_allowed" value="{{ $value }}">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form__group">
            <label class="form__label">Фотографии площадки (до 10 штук, max 5MB)</label>
            <input type="file" wire:model="photos" multiple accept="image/*" class="form__input">
            @error('photos.*') <span class="form__error">{{ $message }}</span> @enderror

            @if(!empty($photos))
                <div class="spot-form__photos-preview">
                    @foreach($photos as $photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="preview">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="spot-form__actions">
            <button type="submit" class="btn btn--primary btn--lg">
                <span wire:loading.remove>
                    {{ $spotId ? 'Сохранить изменения' : 'Отправить на модерацию' }}
                </span>
                <span wire:loading>Сохраняем...</span>
            </button>
            <a href="{{ route('partner.spots') }}" class="btn btn--outline btn--lg">Отмена</a>
        </div>

    </form>
</div>
