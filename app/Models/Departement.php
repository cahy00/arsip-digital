<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug'
    ];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function dispotition()
    {
        return $this->hasMany(Dispotition::class);
    }
}
