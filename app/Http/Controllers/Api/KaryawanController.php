<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Karyawan;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function create(Request $request){
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'jabatan_id' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|numeric',
            'username' => ['required', Rule::unique('karyawans')->whereNull('deleted_at')],
            'foto' => 'required|mimes:jpeg,bmp,png',
            'gaji' => 'nullable',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $karyawanData = collect($request)->only(Karyawan::filters())->all();
        
        $image_name = 'gambar'.\Str::random(5).str_replace(' ', '', $karyawanData['username']);
        $file = $karyawanData['foto'];
        $extension = $file->getClientOriginalExtension();

        $uploadDoc = $request->foto->storeAs(
            'img_karyawan',
            $image_name.'.'.$extension,
            ['disk' => 'public']
        );

        $karyawanData['foto'] = $uploadDoc;
        $karyawanData['password'] = Hash::make($request->no_telp);

        $karyawan = Karyawan::create($karyawanData);

        return response([
            'message' => 'Berhasil Menambahkan Data Karyawan',
            'data' => $karyawan,
        ], 200);
    }

    public function update(Request $request, $id){
        $data = Karyawan::find($id);
        
        if(is_null($data)){
            return response([
                'message' => 'Data Karyawan Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validator = Validator::make($updateData, [
            'jabatan_id' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|numeric',
            'username' => ['required', Rule::unique('karyawans')->ignore($data->id)->whereNull('deleted_at')],
            'password' => 'nullable',
            'foto' => 'nullable',
            'gaji' => 'nullable',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $karyawanData = collect($request)->only(Karyawan::filters())->all();

        if(isset($request->foto)){
            if(isset($data->foto)){
                Storage::delete("public/".$data->foto);
            }
            $image_name = 'gambar'.\Str::random(5).$request->jenis_kendaraan_id.str_replace(' ', '', $karyawanData['nama']);
            $file = $karyawanData['foto'];
            $extension = $file->getClientOriginalExtension();

            $uploadDoc = $request->foto->storeAs(
                'img_karyawan',
                $image_name.'.'.$extension,
                ['disk' => 'public']
            );
    
            $karyawanData['foto'] = $uploadDoc;
        }

        $data->update($karyawanData);

        return response([
            'message' => 'Berhasil Mengubah Data Karyawan',
            'data' => $data,
        ], 200);
    }

    public function delete($id){
        $data = Karyawan::find($id);

        if(is_null($data)){
            return response([
                'message' => 'Data Karyawan Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $data->delete();

        return response([
            'message' => 'Berhasil Menghapus Data Karyawan',
        ], 200);
    }

    public function get($id){
        $data = Karyawan::with(['jabatan'])->where('id', $id)->first();

        if(!is_null($data)){
            return response([
                'message' => 'Tampil Data Karyawan Berhasil!',
                'data' => $data,
            ], 200);
        }

        return response([
            'message' => 'Data Karyawan Tidak Ditemukan',
            'data' => null,
        ], 404);
    }

    public function getAll(){
        $data = Karyawan::with(['jabatan'])->get();

        return response([
            'message' => 'Tampil Data Karyawan Berhasil!',
            'data' => $data,
        ], 200);
    }

    public function listKaryawan(){
        $list = [];
        $karyawans = Karyawan::where('status', 'Aktif')->get();
        
        $list = $karyawans->transform(function($karyawan){
            return[
                'id' => $karyawan->id,
                'nama' => $karyawan->nama
            ];
        });
        return $list;
    }

    public function listKaryawanPenjagaKedai(){
        $list = [];
        $karyawans = Karyawan::whereHas('jabatan', function($q){
            $q->where('nama', 'Penjaga Kedai');
        })->where('status', 'Aktif')->get();
        
        $list = $karyawans->transform(function($karyawan){
            return[
                'id' => $karyawan->id,
                'nama' => $karyawan->nama
            ];
        });
        return $list;
    }

    public function listKaryawanKasir(){
        $list = [];
        $karyawans = Karyawan::whereHas('jabatan', function($q){
            $q->where('nama', 'Kasir');
        })->where('status', 'Aktif')->get();
        
        $list = $karyawans->transform(function($karyawan){
            return[
                'id' => $karyawan->id,
                'nama' => $karyawan->nama
            ];
        });
        return $list;
    }

    public function listKaryawanPencuci(){
        $karyawans = Karyawan::whereHas('jabatan', function($q){
            $q->where('nama', 'Pencuci');
        })->where('status', 'Aktif')->get();
        
        return $karyawans;
    }

    public function updateProfil(Request $request, $id){
        $data = Karyawan::find($id);

        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'jabatan_id' => 'required',
            'nama' => 'required',
            'no_telp' => 'required|numeric',
            'username' => ['required', Rule::unique('karyawans')->ignore($data->id)->whereNull('deleted_at')],
            // 'status' => 'required',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        $karyawanData = collect($request)->only(Karyawan::filters())->all();
        $data->update($karyawanData);

        return response([
            'message' => 'Berhasil Mengubah Profil',
            'data' => $data,
        ], 200);
    }

    public function updateFoto(Request $request, $id){
        $data = Karyawan::find($id);

        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'foto' => 'required|mimes:jpeg,bmp,png',
        ]);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        if(isset($data->foto)){
            Storage::delete("public/".$data->foto);
        }
        $image_name = 'gambar'.\Str::random(5).str_replace(' ', '', $data->username);
        $file = $request->foto;
        $extension = $file->getClientOriginalExtension();

        $uploadDoc = $request->foto->storeAs(
            'img_karyawan',
            $image_name.'.'.$extension,
            ['disk' => 'public']
        );

        $karyawanData['foto'] = $uploadDoc;
        $data->update($karyawanData);

        return response([
            'message' => 'Berhasil Mengubah Foto Profil',
            'data' => $data,
        ], 200);
    }

    public function updatePassword(Request $request, $id){
        $data = Karyawan::find($id);

        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'password' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required',
        ]);

        $checkedPass = Hash::check($request->password, $data->password);
        if(!$checkedPass){
            return response([
                'message' => 'Password Lama Anda Salah!',
                'data' => null
            ], 400);
        }

        $karyawanData['password'] = Hash::make($request->newPassword);
        $data->update($karyawanData);

        if($validator->fails()){
            return response([
                'message' => $validator->messages()->all()
            ], 400);
        }

        return response([
            'message' => 'Berhasil Mengubah Kata Sandi',
            'data' => $data,
        ], 200);
    }
}
