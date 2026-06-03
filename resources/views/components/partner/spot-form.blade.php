<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    {{-- Карта выбора адреса --}}
    <div class="form__group">
        <label class="form__label">Укажите расположение на карте</label>
        <div id="spot-map" style="height:300px; border-radius:8px; border:1px solid #e5e7eb; margin-bottom:8px"></div>
        <p style="font-size:12px; color:#9ca3af">Кликните на карту чтобы указать точное расположение</p>
        <input type="hidden" wire:model="lat" id="spot-lat">
        <input type="hidden" wire:model="lng" id="spot-lng">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.getElementById('spot-map')) return;

            const map = L.map('spot-map').setView([47.0245, 28.8322], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            let marker = null;

            map.on('click', function(e) {
                const { lat, lng } = e.latlng;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);

                document.getElementById('spot-lat').value = lat;
                document.getElementById('spot-lng').value = lng;

                // Обновляем Livewire
            @this.set('lat', lat.toString());
            @this.set('lng', lng.toString());

                // Reverse geocoding map.md
                fetch(`https://map.md/api/companies/webmap/near?lat=${lat}&lon=${lng}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const street = data.find(p => p.type === 'street');
                            if (street) {
                            @this.set('address', street.name);
                            }
                        }
                    })
                    .catch(() => {});
            });
        });
    </script>
</div>
