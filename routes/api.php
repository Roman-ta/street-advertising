<?php

use Illuminate\Support\Facades\Route;

Route::get('/spots/map', function () {
    $spots = \App\Models\Spot::where('status', 'active')
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->with('mainPhoto')
        ->get(['id', 'title', 'type', 'address', 'price_month', 'lat', 'lng']);

    return response()->json($spots->map(fn($spot) => [
        'id'      => $spot->id,
        'title'   => $spot->title,
        'type'    => $spot->type,
        'address' => $spot->address,
        'price'   => $spot->price_month,
        'lat'     => $spot->lat,
        'lng'     => $spot->lng,
        'url'     => route('spots.show', $spot->id),
        'photo'   => $spot->mainPhoto
            ? asset('storage/' . $spot->mainPhoto->path)
            : null,
    ]));
});
