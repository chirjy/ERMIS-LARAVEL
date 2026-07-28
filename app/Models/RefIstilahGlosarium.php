<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefIstilahGlosarium extends Model
{
    protected $table = 'ref_istilah_glosariums';

    protected $fillable = ['nomor_urut', 'istilah', 'definisi', 'referensi_bab', 'konteks_pemakaian'];

    protected $casts = [
        'konteks_pemakaian' => 'array',
    ];
}
