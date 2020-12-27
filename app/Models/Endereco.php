<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        "uf",
        "cidade",
        "bairro",
        "logradouro",
        "numero",
        "complemento",
        "usuario_id",
    ];

    protected $hidden = [
        "id",
        "usuario_id",
        "created_at",
        "updated_at",
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, "uf", "sigla");
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
