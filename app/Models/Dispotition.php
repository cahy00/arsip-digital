<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\DisposisiWhatsAppNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispotition extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_in_id', 'employee_id', 'departement_id', 'ket'
    ];

    public static function boot()
    {
        parent::boot();
        // Saat Disposisi dibuat, update status surat
        static::created(function ($dispotition) {
            $dispotition->letterIn()->update(['status_disposisi' => 'sudah_disposisi']);
            $dispotition->progress()->create([
                'status_progress' => 'belum_selesai',
                'ket' => 'disposisi awal dari pimpinan',
                'document-progress' => 'belum ada'
            ]);
            $pegawai = $dispotition->employee;
//            $pegawai->notify(new DisposisiWhatsAppNotification($disposition));
        });

        // Saat Disposisi dihapus, cek apakah masih ada disposisi, jika tidak ubah status jadi "Belum Disposisi"
        static::deleted(function ($dispotition) {
            if (!$dispotition->letterIn->dispotition()->exists()) {
                $dispotition->letterIn()->update(['status_disposisi' => 'belum_disposisi']);
            }
        });
    }

//    public function scopeForUserBidang($query)
//    {
//        $user = auth()->user();
//
//        if ($user->departement_id) {
//            return $query->where('departement_id', $user->bidang_id);
//        }
//
//        return $query; // Jika user tidak memiliki bidang, tampilkan semua surat
//    }

    public function letterIn()
    {
        return $this->belongsTo(LetterIn::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }


    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    public function getStatusAttribute()
    {
        return $this->progress()->latest()->first()?->status_progress ?? 'belum_selesai';
    }
}
