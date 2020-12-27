<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        "cidade",
        "logradouro",
        "estado_id",
    ];

    public function estado()
    {   
        return $this->belongsTo(Estado::class);
    }
}
