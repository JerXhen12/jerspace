@extends('layouts.admin.main')
@section('title', 'Admin Dashboard')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Daftar Distributor</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mb-4">
                    <div class="col">
                        <a href="{{ route('distributor.create') }}" class="btn btn-primary">Tambah Distributor</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>List Distributor</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Distributor</th>
                                            <th>Lokasi</th>
                                            <th>Kontak</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributors as $distributor)
                                            <tr>
                                                <td>{{ $distributor->nama_distributor }}</td>
                                                <td>{{ $distributor->lokasi }}</td>
                                                <td>{{ $distributor->kontak }}</td>
                                                <td>{{ $distributor->email }}</td>
                                                <td>
                                                    <a href="{{ route('distributor.show', $distributor->id) }}" class="btn btn-info">Detail</a>
                                                    <a href="{{ route('distributor.edit', $distributor->id) }}" class="btn btn-warning">Edit</a>
                                                    <button class="btn btn-danger" onclick="deleteDistributor({{ $distributor->id }})">Hapus</button>
                                                    
                                                    <!-- Form Hapus -->
                                                    <form id="delete-form-{{ $distributor->id }}" action="{{ route('distributor.destroy', $distributor->id) }}" method="POST" style="display: none;">
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
                </div>
            </div>
        </section>
    </div>

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert untuk Hapus -->
    <script>
        function deleteDistributor(id) {
            Swal.fire({
                title: 'Hapus Data!',
                text: "Apakah Anda yakin ingin menghapus distributor ini?",
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

    <!-- SweetAlert untuk Flash Message -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',  // Ini memastikan tombol OK muncul
                timer: null,  // Jika Anda ingin tombol OK muncul tanpa timer, hapus pengaturan timer
                showConfirmButton: true  // Ini menampilkan tombol OK
            });
        </script>
    @endif
@endsection
