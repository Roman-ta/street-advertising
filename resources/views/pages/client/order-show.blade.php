@extends('layouts.app')

@section('title', 'Заказ — AdSpot')

@section('content')
    <livewire:client.order-show :id="(int)$id" />
@endsection
