<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\MobilPelanggan;
use App\Model\TransaksiPencucian;
use Illuminate\Http\Request;

class MobilPelangganController extends Controller
{
    public function get($id){
        $data = MobilPelanggan::with(['transaksis'])->where('id', $id)->first();

        if(!is_null($data)){
            return response([
                'message' => 'Tampil Data Mobil Pelanggan Berhasil!',
                'data' => $data,
            ], 200);
        }

        return response([
            'message' => 'Data Mobil Pelanggan Tidak Ditemukan',
            'data' => null,
        ], 404);
    }

    public function getAll(){

        $data = MobilPelanggan::with(['transaksis'])->get();

        return response([
            'message' => 'Tampil Data Mobil Pelanggan Berhasil!',
            'data' => $data,
        ], 200);
    }

    public function getTransaksiByMobilPelanggan($id){
        $data = TransaksiPencucian::where('mobil_pelanggan_id', $id)->get();

        return $data;
    }
}
