<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = ['makanan_id', 'nama_pemesan', 'nomor_telepon', 'status'];

    public function makanan()
    {
        return $this->belongsTo(Makanan::class);
    }
}
