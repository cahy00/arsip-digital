<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;
    protected $table = 'progress';

    protected $primaryKey = 'id';
    protected $fillable = [
        'dispotition_id',
        'status_progress',
        'ket'
    ];

//    public static function boot()
//    {
//        parent::boot();
//
//        // Saat Disposisi dibuat, update status surat
//        static::created(function ($progress) {
//            $progress->dispotition()->update(['status_disposisi' => 'sudah_disposisi']);
//            $pegawai = $progress->employee;
////            $pegawai->notify(new DisposisiWhatsAppNotification($disposition));
//        });
//
//        // Saat Disposisi dihapus, cek apakah masih ada disposisi, jika tidak ubah status jadi "Belum Disposisi"
//        static::deleted(function ($disposition) {
//            if (!$disposition->letterIn->disposition()->exists()) {
//                $disposition->letterIn()->update(['status_disposisi' => 'belum_disposisi']);
//            }
//        });
//    }
    public function dispotition()
    {
        return $this->belongsTo(Dispotition::class);
    }
}
