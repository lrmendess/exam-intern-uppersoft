<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $fillable = [
        "codigo_ibge",
        "nome",
        "sigla",
    ];


    public function enderecos()
    {
        return $this->hasMany(Endereco::class, "sigla", "uf");
    }
}
