@extends('layouts.admin.main')

@section('title', 'Flash Sale')

@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Flash Sale</h1>
        </div>
        <div class="section-body">
            <a href="{{ route('admin.flash_sale.create') }}" class="btn btn-icon icon-left btn-primary">
                <i class="fas fa-plus"></i> Tambah Flash Sale
            </a>

            <div class="card mt-3">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Diskon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($flashSales as $flashSale)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @foreach($flashSale->product as $product)
                                            {{ $product->name }} <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($flashSale->product as $product)
                                            {{ $product->pivot->discount_price }}% <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <!-- Tombol Detail -->
                                        <a href="{{ route('admin.flash_sale.show', $flashSale->id) }}" class="btn btn-info">Detail</a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.flash_sale.edit', $flashSale->id) }}" class="btn btn-warning">Edit</a>

                                        <!-- Tombol Hapus dengan SweetAlert -->
                                        <button class="btn btn-danger" onclick="deleteFlashSale({{ $flashSale->id }})">Hapus</button>
                                        
                                        <!-- Form untuk penghapusan -->
                                        <form id="delete-form-{{ $flashSale->id }}" action="{{ route('admin.flash_sale.delete', $flashSale->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deleteFlashSale(id) {
        Swal.fire({
            title: 'Hapus Data!',
            text: "Apakah Anda yakin ingin menghapus flash sale ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Tidak, batalkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
