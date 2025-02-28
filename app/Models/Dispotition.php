<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispotition extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_in_id', 'employee_id', 'departement_id', 'ket'
    ];

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
