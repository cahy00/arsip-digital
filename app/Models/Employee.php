<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'departement_id', 'position', 'nip', 'nomor_hp'
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function dispotition()
    {
        return $this->hasMany(Dispotition::class);
    }
}
