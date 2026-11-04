@extends('layouts.app')

@section('title', 'Edit jenis kendaraan')
@section('eyebrow', 'Pengaturan admin')

@section('content')
    <div class="mx-auto max-w-xl">
        <x-ui.card title="Edit jenis kendaraan" :description="$vehicleType->name">
            <form method="POST" action="{{ route('vehicle-types.update', $vehicleType) }}">
                @csrf
                @method('PUT')
                @include('vehicle-types._form')
            </form>
        </x-ui.card>
    </div>
@endsection
