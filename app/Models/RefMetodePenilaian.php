<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefMetodePenilaian extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'cocok_untuk'];
}
