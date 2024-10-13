@extends('layouts.user.main')

@section('content')
<section class="banner-area organic-breadcrumb">
    <div class="container">
        <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
            <div class="col-first">
                <h1>Detail Produk</h1>
                <nav class="d-flex align-items-center">
                    <a href="{{ route('user.dashboard') }}">Home<span class="lnr lnr-arrow-right"></span></a>
                    <a href="#">Detail Produk</a>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="single-prd-item">
                    <img class="img-fluid" src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="s_product_text">
                    <h3>{{ $product->name }}</h3>
                    <h2>{{ number_format($product->price, 0, ',', '.') }} Points</h2>
                    <ul class="list">
                        <li>
                            <a href="#"><span>Kategori</span> : {{ $product->category }}</a>
                        </li>
                    </ul>

                    @if ($product->flashSales->isNotEmpty())
                        @foreach ($product->flashSales as $flashSale)
                            <!-- Harga Diskon dan Countdown dalam satu baris -->
                            <div class="discount-container d-flex align-items-center mt-3">
                                <h4 class="m-0">
                                    <strike>{{ number_format($product->price, 0, ',', '.') }} Points</strike>
                                    <strong class="ml-2">
                                        {{ number_format($product->price - (($flashSale->pivot->discount_price / 100) * $product->price), 0, ',', '.') }} Points
                                    </strong>
                                </h4>

                                <!-- Teks Berakhir Dalam dan Countdown Timer -->
                                <div class="ml-auto d-flex align-items-center small-text">
                                    <span>Berakhir Dalam:</span>
                                    <span id="countdown{{ $flashSale->id }}" class="ml-1 text-danger"></span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h4><strong>Tidak ada diskon saat ini.</strong></h4>
                    @endif

                    <!-- Deskripsi Produk -->
                    <p>{{ $product->description }}</p>

                    <div class="card_area d-flex align-items-center">
                        <a class="primary-btn" href="javascript:void(0);" 
                           onclick="confirmPurchase('{{ $product->id }}', '{{ Auth::user()->id }}')">
                            Beli Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script Countdown -->
<script>
    @foreach ($product->flashSales as $flashSale)
        var endDate{{ $flashSale->id }} = new Date("{{ $flashSale->end_time }}").getTime();
        var countdownInterval{{ $flashSale->id }} = setInterval(function() {
            var now = new Date().getTime();
            var distance = endDate{{ $flashSale->id }} - now;

            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown{{ $flashSale->id }}").innerHTML = 
                hours + "h " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(countdownInterval{{ $flashSale->id }});
                document.getElementById("countdown{{ $flashSale->id }}").innerHTML = 
                    "Flash Sale Berakhir";
            }
        }, 1000);
    @endforeach
</script>

<!-- CSS -->
<style>
    .discount-container {
        display: flex;
        align-items: center;
    }

    .small-text {
        font-size: 0.9rem;
    }

    .ml-2 {
        margin-left: 0.5rem;
    }

    .ml-auto {
        margin-left: auto;
    }

    .text-danger {
        color: #dc3545;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmPurchase(productId, userId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan membeli produk ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Beli!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/product/purchase/' + productId + '/' + userId;
            }
        });
    }
</script>
@endsection
