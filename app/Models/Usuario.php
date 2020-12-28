<?php

namespace App\Models;

use App\Rules\TelefoneValidationRule;
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
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    public function endereco()
    {
        return $this->hasOne(Endereco::class, "id", "id");
    }

    /**
     * Validate Usuario properties.
     * 
     * @param array $properties
     * @param array $overrideRules
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validate(array $properties, array $customRules = [])
    {
        $properties = self::sanitize($properties);

        $rules = [
            "nome"            => ["required", "max:255", "string"],
            "cpf"             => ["required", "min:11", "max:14", "cpf", "unique:usuarios,cpf"],
            "data_nascimento" => ["required", "date", "date_format:Y-m-d"],
            "email"           => ["required", "max:255", "email", "unique:usuarios,email"],
            "telefone"        => ["required", "min:8", "max:20", "string", new TelefoneValidationRule],
            "endereco"        => ["required"],
                "endereco.uf"          => ["required", "min:2", "max:2", "string", "exists:estados,sigla"],
                "endereco.cidade"      => ["required", "max:255", "string"],
                "endereco.bairro"      => ["required", "max:255", "string"],
                "endereco.logradouro"  => ["required", "max:255", "string"],
                "endereco.numero"      => ["required", "integer"],
                "endereco.complemento" => ["nullable", "max:255", "string"],
        ];
        
        // Override or add specified validation rules.
        $rules = array_merge($rules, $customRules);

        return validator()->make($properties, $rules);
    }
    
    /**
     * Sanitize Usuario properties.
     * 
     * @param array $properties
     * 
     * @return array (Usuario)
     */
    public static function sanitize(array $properties)
    {
        // Get only digits.
        $properties["cpf"]      = preg_replace('/[^0-9]/', '', $properties["cpf"]);
        $properties["telefone"] = preg_replace('/[^0-9]/', '', $properties["telefone"]);

        return $properties;
    }
}
