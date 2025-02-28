<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_surat',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_masuk',
        'asal_surat',
        'sifat_surat',
        'kategori_surat',
        'file',
        'status_disposisi'
    ];

    public function dispotition()
    {
        return $this->hasOne(Dispotition::class, 'letter_in_id', 'id');
    }
}
