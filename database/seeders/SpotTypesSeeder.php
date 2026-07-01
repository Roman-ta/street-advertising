<?php

namespace Database\Seeders;

use App\Models\SpotType;
use Illuminate\Database\Seeder;

class SpotTypesSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // Существующие (наружная/транспорт/индор)
            ['slug' => 'billboard',  'name_ru' => 'Билборд',         'name_ro' => 'Panou publicitar', 'name_en' => 'Billboard',       'icon' => '📋', 'category' => 'outdoor', 'sort_order' => 1],
            ['slug' => 'lightbox',   'name_ru' => 'Лайтбокс',        'name_ro' => 'Lightbox',         'name_en' => 'Lightbox',        'icon' => '💡', 'category' => 'outdoor', 'sort_order' => 2],
            ['slug' => 'led_screen', 'name_ru' => 'LED экран',       'name_ro' => 'Ecran LED',        'name_en' => 'LED screen',      'icon' => '📺', 'category' => 'outdoor', 'sort_order' => 3],
            ['slug' => 'banner',     'name_ru' => 'Баннер',          'name_ro' => 'Banner',           'name_en' => 'Banner',          'icon' => '🏷', 'category' => 'outdoor', 'sort_order' => 4],
            ['slug' => 'transport',  'name_ru' => 'Транспорт',       'name_ro' => 'Transport',        'name_en' => 'Transport',       'icon' => '🚌', 'category' => 'outdoor', 'sort_order' => 5],
            ['slug' => 'indoor',     'name_ru' => 'В помещении',     'name_ro' => 'Interior',         'name_en' => 'Indoor',          'icon' => '🏢', 'category' => 'indoor',  'sort_order' => 6],
            ['slug' => 'digital',    'name_ru' => 'Digital',         'name_ro' => 'Digital',          'name_en' => 'Digital',         'icon' => '📱', 'category' => 'digital', 'sort_order' => 7],
            ['slug' => 'event',      'name_ru' => 'Event',           'name_ro' => 'Eveniment',        'name_en' => 'Event',           'icon' => '🎪', 'category' => 'outdoor', 'sort_order' => 8],

            // Новые от ПМ
            ['slug' => 'radio',      'name_ru' => 'Радио',           'name_ro' => 'Radio',            'name_en' => 'Radio',           'icon' => '📻', 'category' => 'media',   'sort_order' => 9],
            ['slug' => 'blogger',    'name_ru' => 'Блогер',          'name_ro' => 'Blogger',          'name_en' => 'Blogger',         'icon' => '📲', 'category' => 'media',   'sort_order' => 10],
            ['slug' => 'youtube',    'name_ru' => 'YouTube',         'name_ro' => 'YouTube',          'name_en' => 'YouTube',         'icon' => '▶️', 'category' => 'media',   'sort_order' => 11],
            ['slug' => 'classified', 'name_ru' => 'Доска объявлений', 'name_ro' => 'Anunțuri',        'name_en' => 'Classifieds',     'icon' => '📰', 'category' => 'digital', 'sort_order' => 12],
        ];

        foreach ($types as $type) {
            SpotType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
