<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksiPencuci extends Model
{
    use SoftDeletes;

    protected $guarded = [
        "id",
        "created_at",
        "updated_at",
    ];

    public function transaksi_pencucian(){
        return $this->belongsTo(TransaksiPencucian::class);
    }

    public function karyawan(){
        return $this->belongsTo(Karyawan::class);
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
