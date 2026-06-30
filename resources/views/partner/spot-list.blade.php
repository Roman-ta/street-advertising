<div class="partner-page">

    <div class="partner-header">
        <h2>Мои площадки</h2>
        <a href="{{ route('partner.spots.create') }}" class="btn btn--primary">
            + Добавить площадку
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    @if($spots->isEmpty())
        <div class="spot-list__empty">
            <p>У вас пока нет площадок</p>
            <a href="{{ route('partner.spots.create') }}">Добавить первую площадку</a>
        </div>
    @else
        <div class="spot-list">
            @foreach($spots as $spot)
                <div class="spot-row">

                    <div class="spot-row__photo">
                        @if($spot->mainPhoto)
                            <img src="{{ Storage::url($spot->mainPhoto->path) }}" alt="{{ $spot->title }}">
                        @else
                            <div class="spot-row__photo-empty">Нет фото</div>
                        @endif
                    </div>

                    <div class="spot-row__info">
                        <div class="spot-row__title">{{ $spot->title }}</div>
                        <div class="spot-row__meta">
                            {{ $spot->address }} · ${{ number_format($spot->price_month, 0) }}/мес
                        </div>
                        <span class="spot-status spot-status--{{ $spot->status }}">
                            {{ match($spot->status) {
                                'active'     => 'Активна',
                                'moderation' => 'На модерации',
                                'blocked'    => 'Заблокирована',
                                'draft'      => 'Черновик',
                                default      => $spot->status,
                            } }}
                        </span>
                    </div>

                    <div class="spot-row__actions">
                        <a href="{{ route('partner.spots.availability', $spot->id) }}" class="btn btn--outline btn--sm">
                            📅 Календарь
                        </a>
                        <a href="{{ route('partner.spots.edit', $spot->id) }}" class="btn btn--outline btn--sm">
                            Редактировать
                        </a>
                        <button
                            wire:click="delete({{ $spot->id }})"
                            wire:confirm="Удалить площадку? Это действие нельзя отменить."
                            class="btn btn--danger btn--sm"
                        >
                            Удалить
                        </button>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
