@if($show)
    <div style="
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
">
        <div style="
        background: white;
        padding: 32px;
        border-radius: 12px;
        max-width: 560px;
        width: 90%;
    ">
            <h3 style="margin-top:0">Условия использования платформы</h3>
            <p style="color:#666">Для продолжения необходимо принять условия договора</p>

            {{-- Текст оферты --}}
            <div style="
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 16px;
            margin-bottom: 16px;
            font-size: 14px;
            line-height: 1.6;
        ">
                <p><strong>1.</strong> Платформа AdSpot является посредником между рекламодателями и владельцами рекламных площадок на территории Молдовы.</p>
                <p><strong>2.</strong> Комиссия платформы составляет 10% от суммы каждой сделки.</p>
                <p><strong>3.</strong> Владелец площадки получает 90% от суммы размещения после подтверждения выполнения работ.</p>
                <p><strong>4.</strong> Оплата замораживается в момент бронирования и списывается только после загрузки фотоотчёта.</p>
                <p><strong>5.</strong> Пользователь обязуется предоставлять достоверную информацию при регистрации.</p>
                <p><strong>6.</strong> Платформа оставляет за собой право заблокировать аккаунт при нарушении правил.</p>
                <p><strong>7.</strong> Все споры решаются в соответствии с законодательством Республики Молдова.</p>
            </div>

            {{-- Чекбокс --}}
            <label style="display:flex; align-items:center; gap:10px; margin-bottom:20px; cursor:pointer">
                <input type="checkbox" wire:model="checked" style="width:18px; height:18px">
                <span>Я ознакомлен и принимаю условия договора публичной оферты</span>
            </label>

            {{-- Кнопка --}}
            <button
                wire:click="accept"
                @if(!$checked) disabled @endif
                style="
                width: 100%;
                padding: 14px;
                background: {{ $checked ? '#5B21B6' : '#ccc' }};
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                cursor: {{ $checked ? 'pointer' : 'not-allowed' }};
            "
            >
                Перейти к платформе
            </button>
        </div>
    </div>
@endif
