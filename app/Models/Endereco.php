<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        "id", // PK && Usuario_FK
        "uf",
        "cidade",
        "bairro",
        "logradouro",
        "numero",
        "complemento",
    ];
    
    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, "uf", "sigla");
    }
}
