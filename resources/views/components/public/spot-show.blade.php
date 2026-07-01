<div class="spot-show">

    <a href="{{ route('home') }}" class="spot-show__back">{{ __('messages.spot_show.back') }}</a>

    <div class="spot-show__layout">

        {{-- Левая часть --}}
        <div>

            {{-- Галерея --}}
            @if($spot->photos->isNotEmpty())
                <div class="spot-show__gallery" x-data="{ active: 0, photos: {{ $spot->photos->map(fn($p) => Storage::url($p->path))->toJson() }} }">
                    <div style="position:relative; border-radius:12px; overflow:hidden; margin-bottom:8px;">
                        <img :src="photos[active]" style="width:100%; height:400px; object-fit:cover; display:block; transition:opacity 0.2s;">
                        @if($spot->photos->count() > 1)
                            <button type="button" @click="active = active > 0 ? active - 1 : photos.length - 1" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.5); color:white; border:none; border-radius:50%; width:40px; height:40px; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center;">‹</button>
                            <button type="button" @click="active = active < photos.length - 1 ? active + 1 : 0" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.5); color:white; border:none; border-radius:50%; width:40px; height:40px; font-size:18px; cursor:pointer; display:flex; align-items:center; justify-content:center;">›</button>
                            <div style="position:absolute; bottom:12px; right:12px; background:rgba(0,0,0,0.5); color:white; font-size:12px; padding:4px 10px; border-radius:20px;">
                                <span x-text="active + 1"></span> / {{ $spot->photos->count() }}
                            </div>
                        @endif
                    </div>
                    @if($spot->photos->count() > 1)
                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                            @foreach($spot->photos as $index => $photo)
                                <div @click="active = {{ $index }}" style="cursor:pointer; border-radius:6px; overflow:hidden;" :style="active === {{ $index }} ? 'outline:2px solid #5B21B6' : 'outline:2px solid transparent'">
                                    <img src="{{ Storage::url($photo->path) }}" style="width:72px; height:54px; object-fit:cover; display:block;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="spot-show__no-photo">{{ __('messages.spot_show.no_photo') }}</div>
            @endif

            <h1 class="spot-show__title">{{ $spot->title }}</h1>
            <p class="spot-show__address">📍 {{ $spot->address }}, {{ __('messages.cities.' . $spot->city) }}</p>

            @if($spot->description)
                <div class="spot-show__description">
                    <h3>{{ __('messages.spot_show.description') }}</h3>
                    <p>{{ $spot->description }}</p>
                </div>
            @endif

            <div class="spot-show__specs">
                <h3>{{ __('messages.spot_show.specs') }}</h3>
                <div class="spot-show__specs-grid">
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_type') }}</span>
                        <p>{{ __('messages.types.' . $spot->type) }}</p>
                    </div>
                    @if($spot->size_w && $spot->size_h)
                        <div class="spot-show__spec-item">
                            <span>{{ __('messages.spot_show.spec_size') }}</span>
                            <p>{{ $spot->size_w }}×{{ $spot->size_h }} м</p>
                        </div>
                    @endif
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_traffic') }}</span>
                        <p>{{ __('messages.traffic.' . $spot->traffic) }}</p>
                    </div>
                    <div class="spot-show__spec-item">
                        <span>{{ __('messages.spot_show.spec_lighting') }}</span>
                        <p>{{ $spot->lighting ? __('messages.spot_show.lighting_yes') : __('messages.spot_show.lighting_no') }}</p>
                    </div>
                    @if($spot->min_rental_days > 1)
                        <div class="spot-show__spec-item">
                            <span>Мин. срок</span>
                            <p>{{ $spot->min_rental_days }} дн.</p>
                        </div>
                    @endif
                    @if($spot->file_types_allowed)
                        <div class="spot-show__spec-item spot-show__spec-item--full">
                            <span>{{ __('messages.spot_show.spec_formats') }}</span>
                            <p>{{ implode(', ', array_map('strtoupper', $spot->file_types_allowed)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Правая часть --}}
        <div>
            <div class="spot-show__booking">

                <div class="spot-show__price">{{ money($spot->price_month, 0) }}</div>
                <div class="spot-show__price-hint">
                    {{ __('messages.spot_show.price_per_day', ['price' => money($spot->price_month / 30, 1)]) }}
                </div>

                @if($spot->min_rental_days > 1)
                    <div style="background:#EFF6FF; border:1px solid #BFDBFE; border-radius:8px; padding:10px 14px; margin-bottom:12px; font-size:13px; color:#1E40AF;">
                        ℹ️ Минимальный срок аренды: {{ $spot->min_rental_days }} дн.
                    </div>
                @endif

                {{-- Календарь --}}
                <div
                    x-data="{
                        occupied: {{ json_encode($occupiedDates) }},
                        minDays: {{ $minRentalDays }},
                        dateFrom: '{{ $date_from ?? '' }}',
                        dateTo: '{{ $date_to ?? '' }}',
                        currentYear: new Date().getFullYear(),
                        currentMonth: new Date().getMonth(),
                        calendarError: '',

                        init() {
                            if (this.dateFrom) {
                                const d = new Date(this.dateFrom);
                                this.currentYear = d.getFullYear();
                                this.currentMonth = d.getMonth();
                            }
                        },

                        fmt(date) {
                            return date.getFullYear() + '-' + String(date.getMonth()+1).padStart(2,'0') + '-' + String(date.getDate()).padStart(2,'0');
                        },

                        monthTitle() {
                            return ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'][this.currentMonth] + ' ' + this.currentYear;
                        },

                        prevMonth() {
                            if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; } else this.currentMonth--;
                        },

                        nextMonth() {
                            if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; } else this.currentMonth++;
                        },

                        calendarCells() {
                            const cells = [];
                            const today = new Date(); today.setHours(0,0,0,0);
                            let dow = new Date(this.currentYear, this.currentMonth, 1).getDay() - 1;
                            if (dow < 0) dow = 6;
                            for (let i = 0; i < dow; i++) cells.push({key:'e'+i,day:'',date:null,selectable:false,occupied:false,empty:true,past:false,inRange:false,isFrom:false,isTo:false});
                            const dim = new Date(this.currentYear, this.currentMonth+1, 0).getDate();
                            for (let d = 1; d <= dim; d++) {
                                const date = new Date(this.currentYear, this.currentMonth, d);
                                const ds = this.fmt(date);
                                const isPast = date < today;
                                const isOcc = this.occupied.includes(ds);
                                cells.push({key:ds,day:d,date:ds,occupied:isOcc,past:isPast,selectable:!isPast&&!isOcc,inRange:this.dateFrom&&this.dateTo&&ds>this.dateFrom&&ds<this.dateTo,isFrom:ds===this.dateFrom,isTo:ds===this.dateTo,empty:false});
                            }
                            return cells;
                        },

                        cellStyle(cell) {
                            if (cell.empty) return 'background:transparent;cursor:default;';
                            if (cell.isFrom || cell.isTo) return 'background:#5B21B6;color:white;cursor:pointer;font-weight:700;';
                            if (cell.inRange) return 'background:#EDE9FE;color:#5B21B6;cursor:pointer;';
                            if (cell.occupied) return 'background:#FEE2E2;color:#ef4444;cursor:not-allowed;text-decoration:line-through;';
                            if (cell.past) return 'background:#f3f4f6;color:#d1d5db;cursor:not-allowed;';
                            return 'background:#DCFCE7;color:#15803D;cursor:pointer;';
                        },

                        selectDate(ds) {
                            if (!ds) return;
                            this.calendarError = '';

                            // Клик на уже выбранную дату начала — сброс
                            if (ds === this.dateFrom) {
                                this.dateFrom = '';
                                this.dateTo = '';
                                $wire.call('setDates', '', '');
                                return;
                            }

                            if (!this.dateFrom || (this.dateFrom && this.dateTo)) {
                                this.dateFrom = ds;
                                this.dateTo = '';
                                return;
                            }

                            if (ds <= this.dateFrom) {
                                this.dateFrom = ds;
                                this.dateTo = '';
                                return;
                            }

                            const hasConflict = this.occupied.some(o => o > this.dateFrom && o <= ds);
                            if (hasConflict) { this.calendarError = 'В выбранном периоде есть занятые даты'; return; }

                            const days = Math.round((new Date(ds) - new Date(this.dateFrom)) / 86400000) + 1;
                            if (days < this.minDays) { this.calendarError = 'Минимальный срок аренды: ' + this.minDays + ' дн.'; return; }

                            this.dateTo = ds;
                        }
                    }"
                    x-init="init()"
                    style="margin-bottom:16px;"
                >
                    {{-- Показ выбранных дат --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px;">
                        <div>
                            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">{{ __('messages.spot_show.date_from_label') }}</label>
                            <div style="padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; background:#f9fafb; min-height:42px;">
                                <span x-text="dateFrom || '—'" :style="dateFrom ? 'color:#111' : 'color:#9ca3af'"></span>
                            </div>
                        </div>
                        <div>
                            <label style="font-size:12px; color:#6b7280; display:block; margin-bottom:4px;">{{ __('messages.spot_show.date_to_label') }}</label>
                            <div style="padding:10px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; background:#f9fafb; min-height:42px;">
                                <span x-text="dateTo || '—'" :style="dateTo ? 'color:#111' : 'color:#9ca3af'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Навигация по месяцам --}}
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <button type="button" @click="prevMonth()" style="background:none; border:1px solid #e5e7eb; border-radius:6px; padding:4px 12px; cursor:pointer; font-size:16px; color:#374151;">‹</button>
                        <span style="font-weight:600; font-size:14px;" x-text="monthTitle()"></span>
                        <button type="button" @click="nextMonth()" style="background:none; border:1px solid #e5e7eb; border-radius:6px; padding:4px 12px; cursor:pointer; font-size:16px; color:#374151;">›</button>
                    </div>

                    {{-- Дни недели --}}
                    <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px; margin-bottom:2px;">
                        <template x-for="wd in ['Пн','Вт','Ср','Чт','Пт','Сб','Вс']">
                            <div style="text-align:center; font-size:11px; color:#9ca3af; padding:4px 0;" x-text="wd"></div>
                        </template>
                    </div>

                    {{-- Дни --}}
                    <div style="display:grid; grid-template-columns:repeat(7,1fr); gap:2px;">
                        <template x-for="cell in calendarCells()" :key="cell.key">
                            <div @click="cell.selectable && selectDate(cell.date)" style="text-align:center; padding:7px 2px; border-radius:6px; font-size:13px; transition:all 0.15s;" :style="cellStyle(cell)">
                                <span x-text="cell.day"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Легенда --}}
                    <div style="display:flex; gap:12px; margin-top:10px; flex-wrap:wrap;">
                        <div style="display:flex; align-items:center; gap:4px; font-size:11px; color:#6b7280;">
                            <div style="width:12px; height:12px; border-radius:3px; background:#DCFCE7; border:1px solid #86EFAC; flex-shrink:0;"></div>Свободно
                        </div>
                        <div style="display:flex; align-items:center; gap:4px; font-size:11px; color:#6b7280;">
                            <div style="width:12px; height:12px; border-radius:3px; background:#FEE2E2; border:1px solid #FCA5A5; flex-shrink:0;"></div>Занято
                        </div>
                        <div style="display:flex; align-items:center; gap:4px; font-size:11px; color:#6b7280;">
                            <div style="width:12px; height:12px; border-radius:3px; background:#5B21B6; flex-shrink:0;"></div>Выбрано
                        </div>
                    </div>

                    {{-- Ошибка выбора дат --}}
                    <div x-show="calendarError" x-transition style="
                                margin-top:10px;
                                background:#FEE2E2;
                                color:#991B1B;
                                padding:10px 14px;
                                border-radius:8px;
                                font-size:13px;
                                border:1px solid #FCA5A5;
                                display:flex;
                                align-items:center;
                                gap:8px;
                            ">
                        <span>⚠️</span>
                        <span x-text="calendarError"></span>
                    </div>

                    {{-- Скрытые инпуты — синхронизация с Livewire через wire:model --}}
                    <input
                        type="hidden"
                        wire:model="date_from"
                        :value="dateFrom"
                        @change="$wire.set('date_from', dateFrom)"
                        x-ref="inputFrom"
                    >
                    <input
                        type="hidden"
                        wire:model="date_to"
                        :value="dateTo"
                        x-ref="inputTo"
                    >

                    {{-- Кнопка синхронизации — тригерим когда обе даты выбраны --}}
                    <div x-effect="
                        if (dateFrom && dateTo) {
                            $wire.call('setDates', dateFrom, dateTo);
                        }
                    "></div>

                </div>

                @if($error)
                    <div class="alert alert--error">{{ $error }}</div>
                @endif

                @if(!empty($occupiedDates))
                    <div class="alert alert--warning">{{ __('messages.spot_show.dates_occupied') }}</div>
                @endif

                @if($days > 0 && !$error)
                    <div class="spot-show__calc">
                        <div class="spot-show__calc-row">
                            <span>{{ __('messages.spot_show.days_x', ['price' => money($spot->price_month / 30, 1), 'days' => $days]) }}</span>
                            <span>{{ money($base_price, 2) }}</span>
                        </div>
                        <div class="spot-show__calc-total">
                            <span>{{ __('messages.spot_show.total') }}</span>
                            <span>{{ money($total, 2) }}</span>
                        </div>
                    </div>
                @endif

                @if($days > 0 && !$error)
                    @auth
                        <button wire:click="addToCart" class="btn btn--primary btn--full btn--lg">
                            <span wire:loading.remove>{{ __('messages.spot_show.add_to_cart') }}</span>
                            <span wire:loading>{{ __('messages.spot_show.adding') }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn--primary btn--full btn--lg">{{ __('messages.spot_show.login_to_book') }}</a>
                    @endauth
                @else
                    <button disabled class="btn btn--full btn--lg" style="background:#e5e7eb; color:#9ca3af; cursor:not-allowed">{{ __('messages.spot_show.select_dates') }}</button>
                @endif

                <p class="spot-show__hint">{{ __('messages.spot_show.frozen_hint') }}</p>

                <div class="spot-show__owner">
                    <p>{{ __('messages.spot_show.owner') }}</p>
                    <p>{{ $spot->partner->name }}</p>
                </div>

            </div>
        </div>

    </div>
    {{-- Похожие площадки --}}
    @if($relatedSpots->isNotEmpty())
        <div style="margin-top:48px; padding-top:32px; border-top:1px solid #e5e7eb;">
            <h2 style="font-size:20px; font-weight:700; margin-bottom:20px;">
                📍 Похожие площадки
            </h2>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:20px;">
                @foreach($relatedSpots as $related)
                    <a href="{{ route('spots.show', $related->id) }}" style="text-decoration:none; color:inherit; display:block; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; transition:all 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)';this.style.borderColor='#5B21B6'" onmouseout="this.style.boxShadow='none';this.style.borderColor='#e5e7eb'">
                        <div style="height:160px; background:#f3f4f6; position:relative; overflow:hidden;">
                            @if($related->mainPhoto)
                                <img src="{{ Storage::url($related->mainPhoto->path) }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:13px;">Нет фото</div>
                            @endif
                            <span style="position:absolute; top:8px; left:8px; background:rgba(91,33,182,0.9); color:white; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:600;">
                                {{ __('messages.types.' . $related->type) }}
                            </span>
                        </div>
                        <div style="padding:14px;">
                            <div style="font-weight:600; font-size:14px; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $related->title }}</div>
                            <div style="font-size:12px; color:#6b7280; margin-bottom:10px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">📍 {{ $related->address }}</div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:16px; font-weight:700; color:#5B21B6;">{{ money($related->price_month, 0) }}</span>
                                <span style="font-size:11px; color:#9ca3af;">{{ __('messages.spot.month_short') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
