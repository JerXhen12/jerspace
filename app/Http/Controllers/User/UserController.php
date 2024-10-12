<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FlashSale;
use App\Models\Order;
class UserController extends Controller
{
    public function index()
    {
        $products = Product ::all();

        return view('pages.user.index', compact('products'));
    }
    public function detail_product($id) 
    {
        $product = Product::findOrFail($id);
    
        return view('pages.user.detail', compact('product'));
    }
    
    
    public function purchaseProduct($productId, $userId)
    {
        $product = Product::findOrFail($productId);
        $user = User::findOrFail($userId);

        if ($user->point > $product->price) {
            $totalPoints = $user->point - $product->price;

            $user->update([
                'point' => $totalPoints,
            ]);

            Alert::success('Berhasil!', 'Produk berhasil dibeli!');
            return redirect()->back();
        } 
        else {
            Alert::error('Gagal!', 'Point anda tidak cukup!');
            return redirect()->back();
        }
    }
    public function flashSale()
    {
        $flashSales = FlashSale::with('products')->where('start_time', '<=', now())->where('end_time', '>=', now())->get();
        return view('pages.user.flash_sale', compact('flashSales'));
    }
}
