<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function index()
    {
        return ItemPenjualan::with(['penjualan.pelanggan', 'barang'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required|date',
            'pelanggan' => 'required',
            'warna' => 'required',
            'alamat' => 'required',
            'subtotal' => 'required|integer',
            'items' => 'required|array',
            'items.*.barang' => 'required',
            'items.*.qty' => 'required|integer|min:1'
        ]);

        $nota = 'NT' . '-' . str_pad(Penjualan::count() + 1, 6, '0', STR_PAD_LEFT);

        $penjualan = Penjualan::create([
            'nota' => $nota,
            'tgl' => $validated['tgl'],
            'subtotal' => $validated['subtotal'],
            'warna' => $validated['warna'],
            'alamat' => $validated['alamat'],
            'kode_pelanggan' => $validated['pelanggan'],
        ]);

        $itemPenjualanData = array_map(function ($item) use ($nota) {
            return [
                'nota' => $nota,
                'kode_barang' => $item['barang'],
                'qty' => $item['qty'],
            ];
        }, $validated['items']);

        ItemPenjualan::insert($itemPenjualanData);

        return response()->json(['penjualan' => $penjualan, 'items' => $itemPenjualanData], 201);
    }

    public function show($id)
    {
        return Penjualan::with(['pelanggan', 'itemPenjualan.barang'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'qty' => 'required|integer|min:1',
            'barang' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $itemPenjualan = ItemPenjualan::findOrFail($id);
            $itemPenjualan->update([
                'qty' => $validated['qty'],
                'kode_barang' => $validated['barang'],
            ]);

            $subtotal = ItemPenjualan::where('nota', $itemPenjualan->nota)
                ->join('barangs', 'item_penjualans.kode_barang', '=', 'barangs.kode')
                ->sum(DB::raw('item_penjualans.qty * barangs.harga'));

            Penjualan::where('nota', $itemPenjualan->nota)->update(['subtotal' => $subtotal]);

            DB::commit();

            return response()->json([
                'message' => 'Item penjualan dan subtotal berhasil diperbarui.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui item penjualan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $itemPenjualan = ItemPenjualan::find($id);

            if (!$itemPenjualan) {
                return response()->json([
                    'message' => 'Item penjualan tidak ditemukan.',
                    'success' => false
                ], 404);
            }

            $nota = $itemPenjualan->nota;

            $itemPenjualan->delete();

            $remainingItems = ItemPenjualan::with('barang')
                ->where('nota', $nota)
                ->get();

            $penjualan = Penjualan::where('nota', $nota)->first();

            if ($remainingItems->isEmpty()) {
                if ($penjualan) {
                    $penjualan->delete();
                }
            } else {
                $newSubtotal = $remainingItems->sum(function ($item) {
                    return $item->qty * ($item->barang->harga ?? 0);
                });

                if ($penjualan) {
                    $penjualan->subtotal = $newSubtotal;
                    $penjualan->save();
                }
            }

            return response()->json([
                'message' => 'Item penjualan berhasil dihapus.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus item penjualan: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }


    public function getCountPenjualan()
    {
        try {
            $totalPenjualan = Penjualan::count();
            return response()->json([
                'count' => $totalPenjualan,
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan jumlah penjualan: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function getTotalPendapatan()
    {
        try {
            $totalPendapatan = Penjualan::sum('subtotal');
            return response()->json([
                'count' => $totalPendapatan,
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan total pendapatan: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
