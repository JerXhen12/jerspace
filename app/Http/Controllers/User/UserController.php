<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class UserController extends Controller
{
    

    public function index()
    {
        // Ambil semua produk dan flash sale terkait
        $products = Product::with(['flashSales' => function($query) {
            $query->where('end_time', '>=', Carbon::now('Asia/Jakarta')); // Hanya tampilkan flash sale yang masih aktif
        }])->get();
    
        // Ambil data user yang sedang login (misal untuk poin)
        $userPoints = Auth::user();
    
        // Kirim data ke view
        return view('pages.user.index', compact('products', 'userPoints'));
    }
    
    public function detail_product($id) 
    {
        $product = Product::with(['flashSales' => function ($query) {
            $query->where('end_time', '>=', Carbon::now('Asia/Jakarta')); // Hanya ambil flash sale yang masih berlaku
        }])->findOrFail($id);

        // Pastikan pengguna login sebelum mengambil poin
        if (Auth::check()) {
            $userPoints = Auth::user()->point; // Ambil poin pengguna
        } else {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu!');
        }

        return view('pages.user.detail', compact('product', 'userPoints'));
    }

    
    
    public function purchaseProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $user = Auth::user(); // Ambil pengguna yang sedang login

        // Cek apakah produk sedang diskon (flash sale)
        $flashSale = $product->flashSales()->first(); // Ambil flash sale aktif, jika ada

        if ($flashSale) {
            // Hitung harga setelah diskon
            $discountedPrice = $product->price - (($flashSale->pivot->discount_price / 100) * $product->price);
        } else {
            // Jika tidak ada diskon, gunakan harga normal
            $discountedPrice = $product->price;
        }

        // Cek apakah pengguna memiliki poin yang cukup
        if ($user->point >= $discountedPrice) {
            $totalPoints = $user->point - $discountedPrice;

            // Update poin pengguna
            $user->update([
                'point' => $totalPoints,
            ]);

            Alert::success('Berhasil!', 'Produk berhasil dibeli dengan harga diskon!');
            return redirect()->back();
        } else {
            Alert::error('Gagal!', 'Poin Anda tidak cukup!');
            return redirect()->back();
        }
    }
}