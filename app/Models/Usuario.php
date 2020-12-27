<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "nome",
        "cpf",
        "data_nascimento",
        "email",
        "telefone",
        "endereco_id",
    ];

    public function endereco()
    {
        return $this->hasOne(Endereco::class);
    }
}
