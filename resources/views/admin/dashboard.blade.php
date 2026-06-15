@extends('layouts.app')
@section('title', 'Админ панель — AdSpot')
@section('content')
    <div style="padding:40px; text-align:center">
        <h1 style="font-size:24px; margin-bottom:16px">Админ панель</h1>
        <p style="color:#6b7280; margin-bottom:24px">
            Управление платформой доступно через Filament
        </p>
        <a href="/admin" class="btn btn--primary">
            Открыть Filament →
        </a>
    </div>
@endsection
