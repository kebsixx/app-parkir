@extends('layouts.app')

@section('title', 'Edit kendaraan')
@section('eyebrow', 'Master data')

@section('content')
    <div class="mx-auto max-w-2xl">
        <x-ui.card title="Edit kendaraan" :description="$vehicle->plate_number">
            <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
                @csrf
                @method('PUT')
                @include('vehicles._form')
            </form>
        </x-ui.card>
    </div>
@endsection
