@extends('layouts.app')

@section('title', 'Tambah kendaraan')
@section('eyebrow', 'Master data')

@section('content')
    <div class="mx-auto max-w-2xl">
        <x-ui.card title="Kendaraan baru" description="Isi data kendaraan yang akan menggunakan parkir.">
            <form method="POST" action="{{ route('vehicles.store') }}">
                @csrf
                @include('vehicles._form')
            </form>
        </x-ui.card>
    </div>
@endsection
