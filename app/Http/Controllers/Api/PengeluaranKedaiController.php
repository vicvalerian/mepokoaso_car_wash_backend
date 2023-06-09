<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\MenuKedai;
use App\Model\PengeluaranKedai;
use Validator;
use Illuminate\Http\Request;

class PengeluaranKedaiController extends Controller
{
    public function create(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'menu_kedai_id' => 'nullable',
            'nama_barang' => 'required',
            'tgl_pembelian' => 'required',
            'jumlah_barang' => 'required|numeric',
            'harga_pembelian' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $pengeluaranKedaiData = collect($request)->only(PengeluaranKedai::filters())->all();

        if(isset($request->menu_kedai_id)){
            $menuKedai = MenuKedai::find($request->menu_kedai_id);
            $stokKedai = $menuKedai->stok;
            $menuKedai->update([
                'stok' => $stokKedai + $pengeluaranKedaiData['jumlah_barang']
            ]);
        }

        $pengeluaranKedai = PengeluaranKedai::create($pengeluaranKedaiData);

        return response([
            'message' => 'Berhasil Menambahkan Data Pengeluaran Kedai',
            'data' => $pengeluaranKedai,
        ], 200);
    }

    public function update(Request $request, $id){
        $data = PengeluaranKedai::find($id);

        if(is_null($data)){
            return response([
                'message' => 'Data Pengeluaran Kedai Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'menu_kedai_id' => 'nullable',
            'nama_barang' => 'required',
            'tgl_pembelian' => 'required',
            'jumlah_barang' => 'required|numeric',
            'harga_pembelian' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $pengeluaranKedaiData = collect($request)->only(PengeluaranKedai::filters())->all();

        if(!is_null($data->menu_kedai_id)){
            $menuKedai = MenuKedai::find($data->menu_kedai_id);
            $stokKedai = $menuKedai->stok;

            $oldStok = $data->jumlah_barang;
            $menuKedai->update([
                'stok' => $stokKedai - $oldStok
            ]);

            $menuKedaiNew = MenuKedai::find($request->menu_kedai_id);
            $stokKedaiNew = $menuKedaiNew->stok;
            $menuKedaiNew->update([
                'stok' => $stokKedaiNew + $pengeluaranKedaiData['jumlah_barang']
            ]);
        }

        $data->update($pengeluaranKedaiData);

        return response([
            'message' => 'Berhasil Mengubah Data Pengeluaran Kedai',
            'data' => $data,
        ], 200);
    }

    public function delete($id){
        $data = PengeluaranKedai::where('id', $id)->first();

        if(is_null($data)){
            return response([
                'message' => 'Data Pengeluaran Kedai Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response([
            'message' => 'Berhasil Menghapus Data Pengeluaran Kedai',
        ], 200);
    }

    public function get($id){
        $data = PengeluaranKedai::with(['menu_kedai'])->where('id', $id)->first();

        if(!is_null($data)){
            return response([
                'message' => 'Tampil Data Pengeluaran Kedai Berhasil!',
                'data' => $data,
            ], 200);
        }

        return response([
            'message' => 'Data Pengeluaran Kedai Tidak Ditemukan',
            'data' => null,
        ], 404);
    }

    public function getAll(){

        $data = PengeluaranKedai::with(['menu_kedai'])->get();

        return response([
            'message' => 'Tampil Data Pengeluaran Kedai Berhasil!',
            'data' => $data,
        ], 200);
    }
}
