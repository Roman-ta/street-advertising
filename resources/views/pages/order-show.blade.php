@extends('layouts.app')

@section('title', 'Заказ #{{ $id }} — AdSpot')

@section('content')
    <livewire:client.order-show :id="$id" />
@endsection
