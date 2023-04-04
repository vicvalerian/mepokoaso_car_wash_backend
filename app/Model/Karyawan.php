<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use SoftDeletes;

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];

    public function jabatan(){
        return $this->belongsTo(Jabatan::class);
    }

    public function gaji_karyawans(){
        return $this->hasMany(GajiKaryawan::class);
    }

    public function peminjaman_karyawans(){
        return $this->hasMany(PeminjamanKaryawan::class);
    }

    public function transaksi_pencucians(){
        return $this->hasMany(TransaksiPencucian::class);
    }

    public function transaksi_kedais(){
        return $this->hasMany(TransaksiKedai::class);
    }

    public function transaksi_pencucian_pencucis(){
        return $this->belongsToMany(TransaksiPencucian::class, 'transaksi_karyawan_pencucis', 'transaksi_pencucian_id', 'karyawan_id')->withPivot('upah_pencuci');
    }

    public function detail_transaksi_pencucis(){
        return $this->hasMany(DetailTransaksiPencuci::class);
    }

    public static function filters(){
        $instance = new static();
        return $instance->getConnection()->getSchemaBuilder()->getColumnListing($instance->getTable());
    }

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y:m:d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y:m:d H:i:s');
        }
    }
}
