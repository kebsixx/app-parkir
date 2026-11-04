@extends('layouts.app')

@section('title', 'Tambah jenis kendaraan')
@section('eyebrow', 'Pengaturan admin')

@section('content')
    <div class="mx-auto max-w-xl">
        <x-ui.card title="Jenis kendaraan baru" description="Tentukan nama jenis dan tarif parkir per jamnya.">
            <form method="POST" action="{{ route('vehicle-types.store') }}">
                @csrf
                @include('vehicle-types._form')
            </form>
        </x-ui.card>
    </div>
@endsection
