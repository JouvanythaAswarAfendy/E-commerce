@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-10">

        <h1 class="text-2xl font-bold mb-6">Produk Kami</h1>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white shadow rounded-lg p-4">
                <img src="https://via.placeholder.com/150" class="w-full rounded">
                <h2 class="text-lg font-semibold mt-2">Gantungan Kunci</h2>
                <p class="text-gray-500">Rp 10.000</p>
            </div>

            <div class="bg-white shadow rounded-lg p-4">
                <img src="https://via.placeholder.com/150" class="w-full rounded">
                <h2 class="text-lg font-semibold mt-2">Bros Kerudung</h2>
                <p class="text-gray-500">Rp 15.000</p>
            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto mt-10">

        <x-ui.accordion title="Apa itu produk ini?">
            Ini adalah deskripsi produk handmade dari akrilik.
        </x-ui.accordion>

        <x-ui.accordion title="Bagaimana cara beli?">
            Kamu bisa langsung checkout melalui website.
        </x-ui.accordion>

    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 space-y-4">

        <x-ui.alert>
            Ini alert biasa
        </x-ui.alert>

        <x-ui.alert type="error">
            Terjadi kesalahan!
        </x-ui.alert>

        <x-ui.alert type="success">
            Berhasil ditambahkan ke keranjang!
        </x-ui.alert>

    </div>
@endsection
