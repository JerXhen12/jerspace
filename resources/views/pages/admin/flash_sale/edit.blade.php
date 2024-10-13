    @extends('layouts.admin.main')

    @section('title', 'Edit Flash Sale')

    @section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Flash Sale</h1>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.flash_sale.update', $flashSale->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card">
                        <div class="card-body">
                            <!-- Nama Flash Sale -->
                            <div class="form-group">
                                <label>Nama Flash Sale</label>
                                <input type="text" name="name" class="form-control" value="{{ $flashSale->name }}" placeholder="Masukkan nama flash sale">
                            </div>

                            <!-- Waktu Mulai Flash Sale -->
                            <div class="form-group">
                                <label>Waktu Mulai</label>
                                <input type="datetime-local" name="start_time" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($flashSale->start_time)) }}">
                            </div>

                            <!-- Waktu Berakhir Flash Sale -->
                            <div class="form-group">
                                <label>Waktu Berakhir</label>
                                <input type="datetime-local" name="end_time" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($flashSale->end_time)) }}">
                            </div>

                            <!-- Pilih Produk -->
                            <div class="form-group">
                                <label>Pilih Produk</label>
                                <select name="product_id[]" class="form-control" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            @foreach($flashSale->product as $flashProduct)
                                                @if($flashProduct->id == $product->id)
                                                    selected
                                                @endif
                                            @endforeach
                                        >
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Diskon untuk Setiap Produk -->
                            <div class="form-group">
                                <label>Harga Diskon (%)</label>
                                <input type="number" name="discount" class="form-control" value="{{ $flashSale->product->first()->pivot->discount_price }}" placeholder="Masukkan diskon">
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
    @endsection
