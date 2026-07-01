@extends('layouts.app')
@section('content')
    <livewire:partner.spot-availability :spot-id="(int)$spotId" />
@endsection
