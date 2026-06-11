@extends('layouts.app')
@section('content')
    <livewire:partner.spot-form :spotId="(int)$spotId" />
@endsection
