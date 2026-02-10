@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Produk QR</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.produk_qr.update', $produk->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text"
                           name="nama_produk"
                           class="form-control"
                           value="{{ $produk->nama_produk }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Warna</label>
                    <input type="text"
                           name="warna"
                           class="form-control"
                           value="{{ $produk->warna }}"
                           required>
                </div>

                 <div class="mb-3">
                    <label class="form-label">Nama Toko</label>
                    <input type="text"
                           name="nama_toko"
                           class="form-control"
                           value="{{ $produk->nama_toko }}">
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

                <a href="{{ route('admin.produk_qr.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
