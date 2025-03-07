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
        return $this->hasMany(Dispotition::class, 'letter_in_id', 'id');
    }

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'dispotitions')
            ->using(Dispotition::class);
//            ->withPivot('')
    }

//    public function employee()
//    {
//        return $this->belongsToMany(Employee::class, 'dispotitions')
//            ->using(Dispotition::class);
////            ->withPivot('')
//    }

//    public function employee()
//    {
//        return $this->hasMany(Employee::class, 'employee_id');
//    }
}
