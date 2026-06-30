<div class="partner-page">

    <div style="margin-bottom:24px">
        <a href="{{ route('partner.spots') }}" style="color:#5B21B6; font-size:14px">← Назад к площадкам</a>
    </div>

    <div class="partner-header">
        <div>
            <h2>{{ $spot->title }}</h2>
            <p style="color:#6b7280; font-size:14px; margin-top:4px">📍 {{ $spot->address }}</p>
        </div>
    </div>

    @if($success)
        <div class="alert alert--success">{{ $success }}</div>
    @endif

    @if($error)
        <div class="alert alert--error">{{ $error }}</div>
    @endif

    {{-- Блок добавления блокировки --}}
    <div class="spot-form__section">
        <div class="spot-form__section-title">Заблокировать даты вручную</div>
        <p style="font-size:13px; color:#6b7280; margin-bottom:16px;">
            Используйте если площадка занята по договорённости вне платформы
        </p>

        <div class="form__row">
            <div class="form__group">
                <label class="form__label">Дата начала</label>
                <input type="date" wire:model="date_from" min="{{ now()->format('Y-m-d') }}" class="form__input">
                @error('date_from') <span class="form__error">{{ $message }}</span> @enderror
            </div>
            <div class="form__group">
                <label class="form__label">Дата окончания</label>
                <input type="date" wire:model="date_to" min="{{ now()->format('Y-m-d') }}" class="form__input">
                @error('date_to') <span class="form__error">{{ $message }}</span> @enderror
            </div>
        </div>

        <button wire:click="blockDates" class="btn btn--primary">
            <span wire:loading.remove wire:target="blockDates">🔒 Заблокировать даты</span>
            <span wire:loading wire:target="blockDates">Сохраняем...</span>
        </button>
    </div>

    {{-- Список всех занятых периодов --}}
    <div style="margin-top:24px">
        <h3 style="font-size:16px; font-weight:700; margin-bottom:12px;">Все занятые периоды</h3>

        @if($spot->availabilities->isEmpty())
            <div class="spot-list__empty">
                <p>Площадка полностью свободна</p>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($spot->availabilities as $availability)
                    <div style="
                        border:1px solid #e5e7eb;
                        border-radius:8px;
                        padding:14px 16px;
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                    ">
                        <div>
                            <div style="font-weight:600; font-size:14px;">
                                {{ \Carbon\Carbon::parse($availability->date_from)->format('d.m.Y') }}
                                — {{ \Carbon\Carbon::parse($availability->date_to)->format('d.m.Y') }}
                            </div>
                            <span style="
                                display:inline-block; margin-top:4px;
                                padding:2px 8px; border-radius:10px; font-size:11px; font-weight:600;
                                background:{{ $availability->status === 'manual_block' ? '#FEF3C7' : '#D1FAE5' }};
                                color:{{ $availability->status === 'manual_block' ? '#92400E' : '#065F46' }};
                            ">
                                {{ match($availability->status) {
                                    'manual_block' => '🔒 Заблокировано вручную',
                                    'reserved'     => '📋 Бронь клиента',
                                    'occupied'     => '✅ Занято',
                                    default        => $availability->status,
                                } }}
                            </span>
                        </div>

                        @if($availability->status === 'manual_block')
                            <button
                                wire:click="unblockDates({{ $availability->id }})"
                                wire:confirm="Снять блокировку с этих дат?"
                                class="btn btn--outline btn--sm"
                            >
                                Снять блокировку
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
