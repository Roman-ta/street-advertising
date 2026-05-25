<div>
    @if($show)
        <div class="modal-overlay">
            <div class="modal">
                <h3 class="modal__title">Условия использования платформы</h3>
                <p class="modal__subtitle">Для продолжения необходимо принять условия договора</p>

                <div class="modal__scroll">
                    <p><strong>1.</strong> Платформа AdSpot является посредником между рекламодателями и владельцами рекламных площадок на территории Молдовы.</p>
                    <p><strong>2.</strong> Комиссия платформы составляет 10% от суммы каждой сделки.</p>
                    <p><strong>3.</strong> Владелец площадки получает 90% от суммы размещения после подтверждения выполнения работ.</p>
                    <p><strong>4.</strong> Оплата замораживается в момент бронирования и списывается только после загрузки фотоотчёта.</p>
                    <p><strong>5.</strong> Пользователь обязуется предоставлять достоверную информацию при регистрации.</p>
                    <p><strong>6.</strong> Платформа оставляет за собой право заблокировать аккаунт при нарушении правил.</p>
                    <p><strong>7.</strong> Все споры решаются в соответствии с законодательством Республики Молдова.</p>
                </div>

                <label class="modal__checkbox">
                    <input type="checkbox" wire:model="checked">
                    <span>Я ознакомлен и принимаю условия договора публичной оферты</span>
                </label>

                <button
                    wire:click="accept"
                    :disabled="!$wire.checked"
                    class="btn btn--full btn--lg"
                    x-bind:class="$wire.checked ? 'btn--primary' : 'btn--disabled'"
                >
                    Перейти к платформе
                </button>
            </div>
        </div>
    @endif
</div>
