<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
 

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // Pastikan confirmDelete() didefinisikan, jika tidak perlu, bisa dihapus.
        confirmDelete('Hapus Data!', 'Apakah Anda Yakin?');

        return view('pages.admin.product.index', compact('products'));
    }

    public function create()
    {
        return view('pages.admin.product.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'numeric',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Proses upload file jika validasi berhasil
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images/', $imageName);
        } else {
            $imageName = null; // Backup jika tidak ada gambar
        }

        // Buat produk baru
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        // Cek jika produk berhasil ditambahkan
        if ($product) {
            Alert::success('Berhasil!', 'Produk berhasil ditambahkan!');
            return redirect()->route('admin.product');
        } else {
            Alert::error('Gagal!', 'Produk gagal ditambahkan!');
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        $product = Product::findOrFail($id);

        return view('pages.admin.product.detail', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('pages.admin.product.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar opsional
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua data terisi dengan benar.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cari produk berdasarkan id
        $product = Product::findOrFail($id);

        // Update data produk
        $product->name = $request->name;
        $product->price = $request->price;  // Harga yang tersimpan utuh, tidak dipotong diskon
        $product->category = $request->category;
        $product->description = $request->description;

        // Cek jika ada file gambar yang diupload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            // Pindahkan file ke folder public/images
            $request->image->move(public_path('images'), $imageName);

            // Hapus gambar lama jika ada
            if ($product->image && File::exists(public_path('images/' . $product->image))) {
                File::delete(public_path('images/' . $product->image));
            }

            // Simpan nama gambar baru
            $product->image = $imageName;
        }

        // Simpan perubahan produk
        $product->save();

        // Tampilkan pesan sukses dan redirect ke halaman produk admin
        Alert::success('Berhasil!', 'Produk berhasil diperbarui.');
        return redirect()->route('admin.product');
    }

    public function delete($id)
    {
        // Cari produk berdasarkan id
        $product = Product::findOrFail($id);

        // Hapus gambar yang terkait dengan produk dari direktori public/images/
        $oldPath = public_path('images/') . $product->image;
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }

        // Hapus produk dari database
        $product->delete();

        // Cek jika produk berhasil dihapus
        if ($product) {
            Alert::success('Berhasil!', 'Produk berhasil dihapus!');
            return redirect()->back();
        } else {
            Alert::error('Gagal!', 'Produk gagal dihapus!');
            return redirect()->back();
        }
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('pages.user.detail', compact('product'));
    }
    
}
