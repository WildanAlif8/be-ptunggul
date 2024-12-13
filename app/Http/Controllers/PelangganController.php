<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Pelanggan::all();
        return response()->json($pelanggans);
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
        $validated= $request->validate([
            'nama'=> 'required|string|max:255',
            'domisili'=> 'required|string|max:255',
            'jenis_kelamin'=> 'required|in:PRIA,WANITA',
        ]);

        $kode = 'KP'. '-'. str_pad(Pelanggan::count() + 1, 6, '0', STR_PAD_LEFT);
        // Menyimpan data pelanggan ke database
        $pelanggan = Pelanggan::create([
            'kode' => $kode,
            'nama' => $validated['nama'],
            'domisili' => $validated['domisili'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        // Mengembalikan response JSON dengan data pelanggan
        return response()->json($pelanggan, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        return Pelanggan::findOrFail($id);
        return response()->json($pelanggan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Validasi data yang diterima
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'domisili' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|in:PRIA,WANITA',
        ]);
    
        // Update data pelanggan menggunakan data validasi
        $pelanggan->update($validated);
    
        // Kembalikan respon JSON dengan data pelanggan yang telah diperbarui
        return response()->json($pelanggan);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
 public function destroy(Request $request, $id)
{
    try {
        // Cari pelanggan berdasarkan ID dan hapus
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        // Kembalikan respon JSON dengan pesan berhasil
        return response()->json([
            'message' => 'Pelanggan berhasil dihapus.',
            'success' => true
        ], 200);

    } catch (\Exception $e) {
        // Tangani jika terjadi kesalahan
        return response()->json([
            'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage(),
            'success' => false
        ], 500);
    }
}
public function getCountPelanggan()
{
    try {
        $totalPelanggan = Pelanggan::count();
        return response()->json([
            'count' => $totalPelanggan,
            'success' => true
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal mendapatkan jumlah pelanggan: ' . $e->getMessage(),
            'success' => false
        ], 500);
    }
}
    
}
