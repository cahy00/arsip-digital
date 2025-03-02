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
        static::created(function ($disposition) {
            $disposition->letterIn()->update(['status_disposisi' => 'sudah_disposisi']);
            $pegawai = $disposition->employee;
            $pegawai->notify(new DisposisiWhatsAppNotification($disposition));
        });

        // Saat Disposisi dihapus, cek apakah masih ada disposisi, jika tidak ubah status jadi "Belum Disposisi"
        static::deleted(function ($disposition) {
            if (!$disposition->letterIn->disposition()->exists()) {
                $disposition->letterIn()->update(['status_disposisi' => 'belum_disposisi']);
            }
        });

        static::created(function ($disposition) {
            
        });
    }

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
}
