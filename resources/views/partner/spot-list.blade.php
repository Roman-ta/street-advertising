<div>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h2>Мои площадки</h2>
        <a href="{{ route('partner.spots.create') }}" style="
            background:#5B21B6; color:white;
            padding:10px 20px; border-radius:8px;
            text-decoration:none;
        ">+ Добавить площадку</a>
    </div>

    @if(session('success'))
        <div style="background:#D1FAE5; color:#065F46; padding:12px 16px; border-radius:8px; margin-bottom:16px">
            {{ session('success') }}
        </div>
    @endif

    @if($spots->isEmpty())
        <div style="text-align:center; padding:60px; color:#888">
            <p style="font-size:18px">У вас пока нет площадок</p>
            <a href="{{ route('partner.spots.create') }}">Добавить первую площадку</a>
        </div>
    @else
        <div style="display:grid; gap:16px">
            @foreach($spots as $spot)
                <div style="
                border:1px solid #e5e7eb;
                border-radius:12px;
                padding:20px;
                display:flex;
                gap:16px;
                align-items:center;
            ">
                    {{-- Фото --}}
                    <div style="width:100px; height:70px; background:#f3f4f6; border-radius:8px; overflow:hidden; flex-shrink:0">
                        @if($spot->mainPhoto)
                            <img
                                src="{{ Storage::url($spot->mainPhoto->path) }}"
                                style="width:100%; height:100%; object-fit:cover"
                            >
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:12px">
                                Нет фото
                            </div>
                        @endif
                    </div>

                    {{-- Инфо --}}
                    <div style="flex:1">
                        <div style="font-weight:600; font-size:16px">{{ $spot->title }}</div>
                        <div style="color:#6b7280; font-size:14px; margin-top:4px">
                            {{ $spot->address }} · ${{ number_format($spot->price_month, 0) }}/мес
                        </div>
                        <div style="margin-top:8px">
                        <span style="
                            padding:3px 10px; border-radius:20px; font-size:12px;
                            background:{{ match($spot->status) {
                                'active'     => '#D1FAE5',
                                'moderation' => '#FEF3C7',
                                'blocked'    => '#FEE2E2',
                                default      => '#F3F4F6'
                            } }};
                            color:{{ match($spot->status) {
                                'active'     => '#065F46',
                                'moderation' => '#92400E',
                                'blocked'    => '#991B1B',
                                default      => '#374151'
                            } }};
                        ">
                            {{ match($spot->status) {
                                'active'     => 'Активна',
                                'moderation' => 'На модерации',
                                'blocked'    => 'Заблокирована',
                                'draft'      => 'Черновик',
                                default      => $spot->status
                            } }}
                        </span>
                        </div>
                    </div>

                    {{-- Действия --}}
                    <div style="display:flex; gap:8px">
                        <a href="{{ route('partner.spots.edit', $spot->id) }}" style="
                        padding:8px 16px; border-radius:8px;
                        border:1px solid #e5e7eb;
                        text-decoration:none; color:#374151;
                        font-size:14px;
                    ">Редактировать</a>

                        <button
                            wire:click="delete({{ $spot->id }})"
                            wire:confirm="Удалить площадку? Это действие нельзя отменить."
                            style="
                            padding:8px 16px; border-radius:8px;
                            background:#FEE2E2; border:none;
                            color:#991B1B; cursor:pointer;
                            font-size:14px;
                        "
                        >Удалить</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
