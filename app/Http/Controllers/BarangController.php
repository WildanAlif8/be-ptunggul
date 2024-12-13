<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Barang::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        $kode = 'KB'. '-'. str_pad(Barang::count() + 1, 6, '0', STR_PAD_LEFT);
        $barang = Barang::create([
            'kode' => $kode,
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'harga' => $validated['harga'],
        ]);

        return response()->json($barang, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang, $id)
    {
        return Barang::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit(Barang $barang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrfail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:255',
            'harga' => 'sometimes|required|integer',
        ]);
        $barang->update($validated);
        return $barang;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $request, $id)
    {
        try {
            // Cari pelanggan berdasarkan ID dan hapus
            $barang = Barang::findOrFail($id);
            $barang->delete();
    
            // Kembalikan respon JSON dengan pesan berhasil
            return response()->json([
                'message' => 'Barang berhasil dihapus.',
                'success' => true
            ], 200);
    
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json([
                'message' => 'Gagal menghapus data Barang: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function getCountBarang()
    {
        try {
            $totalBarang = Barang::count();
            return response()->json([
                'count' => $totalBarang,
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan jumlah barang: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
