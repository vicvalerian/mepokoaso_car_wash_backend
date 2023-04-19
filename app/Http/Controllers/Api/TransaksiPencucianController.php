<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\DetailTransaksiPencuci;
use App\Model\MobilPelanggan;
use App\Model\TransaksiPencucian;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransaksiPencucianController extends Controller
{
    public function generateNoPencucian(){
        $type = "CUCI";
        $currentTime = now()->format('dmy');
        $numberPrefix = $type.$currentTime.'-';
        $container = TransaksiPencucian::where('no_pencucian','like',$numberPrefix.'%')->orderBy('no_pencucian','desc')->first();

        if($container){
            $counter = (int)(explode($numberPrefix,$container->no_pencucian)[1]) + 1;
            return $numberPrefix.sprintf('%03d', $counter);
        }

        return $numberPrefix.'001';
    }

    public function create(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'kendaraan_id' => 'required',
            'karyawan_id' => 'required',
            'no_pencucian' => ['required', Rule::unique('transaksi_pencucians')->whereNull('deleted_at')],
            'no_polisi' => 'required',
            'jenis_kendaraan' => 'required',
            'tarif_kendaraan' => 'required|numeric',
            'tgl_pencucian' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $pencucianData = collect($request)->only(TransaksiPencucian::filters())->all();

        $jmlPencuci = count($request->detail_transaksi_pencuci);
        $upahPencuci = ($pencucianData['tarif_kendaraan'] * 0.35) / $jmlPencuci;

        $pencucianPencucis = collect($request->detail_transaksi_pencuci)->map(function($pencuci) use($upahPencuci) {
            $pencuci['upah_pencuci'] = $upahPencuci;
            return collect($pencuci)->only(DetailTransaksiPencuci::filters())->all();
        });

        $transaksiPencucian = TransaksiPencucian::create($pencucianData);
        $transaksiPencucian->detail_transaksi_pencucis()->createMany($pencucianPencucis);

        return response([
            'message' => 'Berhasil Menambahkan Data Transaksi Pencucian',
            'data' => $transaksiPencucian,
        ], 200);
    }

    public function update(Request $request, $id){
        $data = TransaksiPencucian::find($id);

        if(is_null($data)){
            return response([
                'message' => 'Data Transaksi Pencucian Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($data->status != 'Baru'){
            return response([
                'message' => 'Data Transaksi Pencucian Sudah Diproses!',
                'data' => null
            ], 400);
        }

        $updateData = $request->all();
        $validator = Validator::make($updateData, [
            'kendaraan_id' => 'required',
            'karyawan_id' => 'required',
            'no_pencucian' => ['required', Rule::unique('transaksi_pencucians')->ignore($data->id)->whereNull('deleted_at')],
            'no_polisi' => 'required',
            'jenis_kendaraan' => 'required',
            'tarif_kendaraan' => 'required|numeric',
            'tgl_pencucian' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $pencucianData = collect($request)->only(TransaksiPencucian::filters())->all();

        $jmlPencuci = count($request->detail_transaksi_pencuci);
        $upahPencuci = ($pencucianData['tarif_kendaraan'] * 0.35) / $jmlPencuci;

        $pencucianPencucis = collect($request->detail_transaksi_pencuci)->map(function($pencuci) use($upahPencuci) {
            $pencuci['upah_pencuci'] = $upahPencuci;
            return collect($pencuci)->only(DetailTransaksiPencuci::filters())->all();
        });

        $data->update($pencucianData);
        $data->detail_transaksi_pencucis()->delete();
        $data->detail_transaksi_pencucis()->createMany($pencucianPencucis);

        return response([
            'message' => 'Berhasil Mengubah Data Transaksi Pencucian',
            'data' => $data,
        ], 200);
    }

    public function delete($id){
        $data = TransaksiPencucian::where('id', $id)->first();

        if(is_null($data)){
            return response([
                'message' => 'Data Transaksi Pencucian Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($data->status != 'Baru'){
            return response([
                'message' => 'Data Transaksi Pencucian Sudah Diproses!',
                'data' => null
            ], 400);
        }

        $data->delete();
        $data->detail_transaksi_pencucis()->delete();

        return response([
            'message' => 'Berhasil Menghapus Data Transaksi Pencucian',
        ], 200);
    }

    public function get($id){
        $data = TransaksiPencucian::with(['kendaraan', 'karyawan', 'detail_transaksi_pencucis', 'karyawan_pencucis'])->where('id', $id)->first();

        if(!is_null($data)){
            return response([
                'message' => 'Tampil Data Transaksi Pencucian Berhasil!',
                'data' => $data,
            ], 200);
        }

        return response([
            'message' => 'Data Transaksi Pencucian Tidak Ditemukan',
            'data' => null,
        ], 404);
    }

    public function getAll(Request $request){
        $status = @$request->status;

		if($status){
			$data = TransaksiPencucian::with(['kendaraan', 'karyawan', 'detail_transaksi_pencucis', 'karyawan_pencucis'])->orderBy("updated_at", "desc")->where('status', $status)->get();
		} else{
            $data = TransaksiPencucian::with(['kendaraan', 'karyawan', 'detail_transaksi_pencucis', 'karyawan_pencucis'])->orderBy("updated_at", "desc")->get();
        }

        return response([
            'message' => 'Tampil Data Transaksi Pencucian Berhasil!',
            'data' => $data,
        ], 200);
    }

    public function prosesCuci(Request $request){
        $transaksi = TransaksiPencucian::with(['kendaraan'])->where('id', $request->id)->first();

        if($transaksi->jenis_kendaraan == 'Mobil'){
            $jml_transaksi = TransaksiPencucian::where('no_polisi', $transaksi->no_polisi)->count();

            $mobilPelanggan = MobilPelanggan::updateOrCreate([
                'no_polisi' => $transaksi->no_polisi,
                'nama_kendaraan' => $transaksi->kendaraan->nama,
            ], [
                'jml_transaksi' => $jml_transaksi,
            ]);

            $transaksi->update(['mobil_pelanggan_id' => $mobilPelanggan->id]);
        }

        $transaksi->update(['status' => 'Proses Cuci']);

        return response([
            'message' => 'Berhasil Mengubah Status Transaksi Pencucian',
        ], 200);
    }

    public function prosesKering(Request $request){
        $transaksi = TransaksiPencucian::with(['kendaraan', 'mobil_pelanggan'])->where('id', $request->id)->first();

        if($transaksi->mobil_pelanggan){
            $mobilPelanggan = $transaksi->mobil_pelanggan;

            if($mobilPelanggan->jml_transaksi == 6){
                $transaksi->update(['is_free' => true]);
            }
        }

        $transaksi->update(['status' => 'Proses Kering']);

        return response([
            'message' => 'Berhasil Mengubah Status Transaksi Pencucian',
        ], 200);
    }

    public function prosesBayar(Request $request){
        $transaksi = TransaksiPencucian::with(['kendaraan'])->where('id', $request->id)->first();

        if($transaksi->is_free == true){
            $transaksi->update(['total_pembayaran' => 0]);
        } else if($transaksi->is_free == false){
            $transaksi->update(['total_pembayaran' => $transaksi->tarif_kendaraan]);
        }

        $transaksi->update(['status' => 'Proses Bayar']);

        return response([
            'message' => 'Berhasil Mengubah Status Transaksi Pencucian',
        ], 200);
    }

    public function finish(Request $request){
        $transaksi = TransaksiPencucian::with(['kendaraan'])->where('id', $request->id)->first();

        if($transaksi->is_free == true){
            $transaksi->update(['keuntungan' => 0]);
        } else if($transaksi->is_free == false){
            $keuntungan = $transaksi->tarif_kendaraan * 0.65;
            $transaksi->update(['keuntungan' => $keuntungan]);
        }

        $transaksi->update(['status' => 'Selesai']);

        return response([
            'message' => 'Berhasil Mengubah Status Transaksi Pencucian',
        ], 200);
    }
}
