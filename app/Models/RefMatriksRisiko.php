<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefMatriksRisiko extends Model
{
    protected $fillable = ['level_kemungkinan', 'level_dampak', 'besaran_risiko'];
}
