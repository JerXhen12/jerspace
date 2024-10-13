<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use App\Models\Product;
use RealRashid\SweetAlert\Facades\Alert;
class FlashSaleController extends Controller
{
    // Method index untuk menampilkan semua Flash Sale
    public function index()
    {
        $flashSales = FlashSale::with('product')->get();

        // SweetAlert konfirmasi penghapusan
        confirmDelete('Hapus Data!', 'Apakah Anda Yakin?');
        
        return view('pages.admin.flash_sale.index', compact('flashSales'));
    }

    // Method create untuk menampilkan form tambah Flash Sale
    public function create()
    {
        $products = Product::all(); // Ambil semua produk untuk dipilih dalam Flash Sale
        return view('pages.admin.flash_sale.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount' => 'required|numeric|min:0|max:100',
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        // Simpan data Flash Sale ke dalam tabel FlashSale
        $flashSale = FlashSale::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // Simpan produk dan diskon ke tabel pivot (flash_sale_product)
        $flashSale->product()->attach($request->product_id, ['discount_price' => $request->discount]);

        // Tampilkan pesan sukses menggunakan SweetAlert
        Alert::success('Berhasil!', 'Flash Sale berhasil ditambahkan!');
        
        return redirect()->route('admin.flash_sale.index');
    }

    public function show($id)
    {
        $flashSale = FlashSale::with('product')->findOrFail($id);
        return view('pages.admin.flash_sale.show', compact('flashSale'));
    }
    public function edit($id)
    {
        // Ambil data flash sale berdasarkan ID
        $flashSale = FlashSale::findOrFail($id);
        $products = Product::all(); // Untuk memilih produk yang akan diubah diskonnya

        return view('pages.admin.flash_sale.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount' => 'required|numeric|min:0|max:100',
            'name' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        // Cari Flash Sale yang akan diupdate
        $flashSale = FlashSale::findOrFail($id);

        // Update data flash sale
        $flashSale->name = $request->name;
        $flashSale->start_time = $request->start_time;
        $flashSale->end_time = $request->end_time;
        $flashSale->save();

        // Update relasi produk dan diskonnya
        $flashSale->product()->sync([]);
        foreach ($request->product_id as $productId) {
            $flashSale->product()->attach($productId, ['discount_price' => $request->discount]);
        }

        // Tampilkan pesan sukses menggunakan SweetAlert
        Alert::success('Berhasil!', 'Flash Sale berhasil diperbarui!');
        
        return redirect()->route('admin.flash_sale.index');
    }

    
    public function destroy($id)
    {
        // Temukan Flash Sale berdasarkan id
        $flashSale = FlashSale::findOrFail($id);

        // Hapus data flash sale
        $flashSale->delete();

        // Tampilkan pesan sukses menggunakan SweetAlert
        Alert::success('Berhasil!', 'Flash Sale berhasil dihapus.');
        
        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.flash_sale.index');
    }
}