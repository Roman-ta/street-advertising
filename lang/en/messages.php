<?php

return [

    // ─── Navigation ───────────────────────────────────────────────────────────
    'nav' => [
        'login'    => 'Login',
        'register' => 'Register',
        'logout'   => 'Logout',
        'cabinet'  => 'Dashboard',
        'cart'     => 'Cart',
    ],

    // ─── Auth ─────────────────────────────────────────────────────────────────
    'auth' => [
        'register_title'    => 'Create an account',
        'register_subtitle' => 'Who are you on the platform?',
        'login_title'       => 'Log in to your account',
    ],

    // ─── Hero ─────────────────────────────────────────────────────────────────
    'hero' => [
        'label'        => '🇲🇩 #1 Platform in Moldova',
        'title'        => 'Your ad. In the right place.',
        'title_line1'  => 'Your ad.',
        'title_line2'  => 'In the right place.',
        'subtitle'     => 'A unified platform for finding and booking advertising spots across Moldova',
        'find'         => 'Find a spot →',
        'place'        => 'List your spot',
        'stats_spots'  => 'Spots',
        'stats_cities' => 'Cities',
        'stats_online' => 'Online',
    ],

    // ─── Catalog ──────────────────────────────────────────────────────────────
    'catalog' => [
        'title'       => 'Advertising Spaces',
        'search'      => 'Search by name or address...',
        'all'         => 'All',
        'all_cities'  => 'All cities',
        'any_traffic' => 'Any traffic',
        'up_to'       => 'Up to',
        'found'       => 'Found: :count spaces',
        'reset'       => 'Reset Filters',
        'on_map'      => 'On the map',
        'map_title'   => 'Spots on the map',
        'all_title'   => 'All spots',
        'empty_title' => 'Nothing found',
        'empty_hint'  => 'Try adjusting your filters',
    ],

    // ─── Spot card ────────────────────────────────────────────────────────────
    'spot' => [
        'month'       => 'per month',
        'month_short' => '/mo',
        'day'         => 'per day',
        'book'        => 'Book Now',
        'add_to_cart' => 'Add to Cart',
        'details'     => 'View Details',
    ],

    // ─── Spot types ───────────────────────────────────────────────────────────
    'types' => [
        'billboard'  => 'Billboard',
        'lightbox'   => 'Lightbox',
        'led_screen' => 'LED Screen',
        'banner'     => 'Banner',
        'transport'  => 'Transport',
        'indoor'     => 'Indoor',
        'digital'    => 'Digital',
        'event'      => 'Event',
    ],

    // ─── Traffic levels ───────────────────────────────────────────────────────
    'traffic' => [
        'high'   => 'High',
        'medium' => 'Medium',
        'low'    => 'Low',
    ],

    // ─── Cities ───────────────────────────────────────────────────────────────
    'cities' => [
        'Chisinau' => 'Chisinau',
        'Balti'    => 'Balti',
        'Cahul'    => 'Cahul',
        'Ungheni'  => 'Ungheni',
        'Soroca'   => 'Soroca',
        'Orhei'    => 'Orhei',
    ],

    // ─── SEO ──────────────────────────────────────────────────────────────────
    'seo' => [
        'home_title' => 'AdSpot — Advertising Spaces in Moldova',
    ],

    // ─── Spot form (add / edit) ───────────────────────────────────────────────
    'spot_form' => [
        'add_title'         => 'Add ad spot',
        'edit_title'        => 'Edit ad spot',
        'add_subtitle'      => 'Fill in the details about your advertising spot',
        'edit_subtitle'     => 'Make your changes and save',
        'section_main'      => 'Basic information',
        'section_location'  => 'Location',
        'section_specs'     => 'Specifications',
        'section_price'     => 'Pricing',
        'section_desc'      => 'Description',
        'section_formats'   => 'Accepted material formats',
        'section_photos'    => 'Spot photos',
        'type_label'        => 'Ad type *',
        'title_label'       => 'Spot name *',
        'title_placeholder' => 'E.g.: Billboard 6×3m, Chisinau city center',
        'address_label'     => 'Address *',
        'city_label'        => 'City *',
        'district_label'    => 'District',
        'district_ph'       => 'Center, Botanica...',
        'map_label'         => 'Pin on map',
        'map_hint'          => 'Click on the map to set the exact location',
        'width_label'       => 'Width (m)',
        'height_label'      => 'Height (m)',
        'traffic_label'     => 'Traffic',
        'lighting_label'    => 'Lighting',
        'lighting_check'    => 'Has lighting',
        'price_label'       => 'Price per month ($) *',
        'desc_placeholder'  => 'Describe the spot\'s features...',
        'photo_click'       => 'Click to select photos',
        'photo_hint'        => 'Up to 10 photos · max 5MB each · JPG, PNG, WebP',
        'photo_loading'     => 'Uploading photos...',
        'photo_main'        => 'Main',
        'btn_save'          => 'Save changes',
        'btn_submit'        => 'Submit for review',
        'btn_saving'        => 'Saving...',
        'btn_cancel'        => 'Cancel',
    ],

    // ─── Partner cabinet ──────────────────────────────────────────────────────
    'partner' => [
        'role'                   => 'Partner · Spot Owner',
        'add_spot'               => 'Add a spot',
        'nav_overview'           => 'Overview',
        'nav_spots'              => 'My spots',
        'nav_orders'             => 'Orders',
        'stat_total_spots'       => 'Total spots',
        'stat_active'            => 'Active',
        'stat_new_orders'        => 'New orders',
        'stat_earned'            => 'Earned',
        'recent_orders'          => 'Recent orders',
        'no_orders'              => 'No orders yet',
        'no_orders_hint'         => 'Add a spot and wait for your first client',
        'no_photo'               => 'No photo',
        'client_label'           => 'Client:',
        'your_share'             => 'your share (90%)',
        'your_share_short'       => 'your share',
        'orders_title'           => 'All orders',
        'back'                   => 'Back',
        'back_to_orders'         => 'Back to orders',
        'filter_all'             => 'All',
        'filter_new'             => 'New',
        'filter_active'          => 'Active',
        'filter_completed'       => 'Completed',
        'orders_not_found'       => 'No orders found',
        'ad_active'              => 'Ad is active',
        'start_label'            => 'Start:',
        'end_label'              => 'End:',
        'client_materials'       => 'Client advertising materials',
        'download'               => 'Download',
        'upload_report_title'    => 'Upload a photo report',
        'upload_report_hint'     => 'Take a photo of the installed ad and upload it here. The rental timer will start automatically once uploaded.',
        'report_click'           => 'Click to select a photo',
        'report_hint'            => 'Photo of the installed ad · JPG, PNG',
        'uploading'              => 'Uploading...',
        'submit_and_start_timer' => 'Submit and start the timer',
        'uploaded_reports'       => 'Uploaded photo reports',
        'view'                   => 'View',
    ],

    // ─── Order statuses ───────────────────────────────────────────────────────
    'order_status' => [
        'pending'         => 'Awaiting payment',
        'paid_pending'    => 'Paid — awaiting materials',
        'materials_ready' => 'Materials ready',
        'active'          => 'Active',
        'completed'       => 'Completed',
        'cancelled'       => 'Cancelled',
    ],

];
