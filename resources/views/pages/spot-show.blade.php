@extends('layouts.app')

@section('title', 'Площадка — AdSpot')

@section('content')
    <livewire:public.spot-show :id="$id" />
@endsection
