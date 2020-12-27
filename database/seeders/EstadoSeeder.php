<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run()
    {   
        $estados = [
            [
                "id" => 11,
                "sigla" => "RO",
                "nome" => "Rondônia",
            ],
            [
                "id" => 12,
                "sigla" => "AC",
                "nome" => "Acre",
            ],
            [
                "id" => 13,
                "sigla" => "AM",
                "nome" => "Amazonas",
            ],
            [
                "id" => 14,
                "sigla" => "RR",
                "nome" => "Roraima",
            ],
            [
                "id" => 15,
                "sigla" => "PA",
                "nome" => "Pará",
            ],
            [
                "id" => 16,
                "sigla" => "AP",
                "nome" => "Amapá",
            ],
            [
                "id" => 17,
                "sigla" => "TO",
                "nome" => "Tocantins",
            ],
            [
                "id" => 21,
                "sigla" => "MA",
                "nome" => "Maranhão",
            ],
            [
                "id" => 22,
                "sigla" => "PI",
                "nome" => "Piauí",
            ],
            [
                "id" => 23,
                "sigla" => "CE",
                "nome" => "Ceará",
            ],
            [
                "id" => 24,
                "sigla" => "RN",
                "nome" => "Rio Grande do Norte",
            ],
            [
                "id" => 25,
                "sigla" => "PB",
                "nome" => "Paraíba",
            ],
            [
                "id" => 26,
                "sigla" => "PE",
                "nome" => "Pernambuco",
            ],
            [
                "id" => 27,
                "sigla" => "AL",
                "nome" => "Alagoas",
            ],
            [
                "id" => 28,
                "sigla" => "SE",
                "nome" => "Sergipe",
            ],
            [
                "id" => 29,
                "sigla" => "BA",
                "nome" => "Bahia",
            ],
            [
                "id" => 31,
                "sigla" => "MG",
                "nome" => "Minas Gerais",
            ],
            [
                "id" => 32,
                "sigla" => "ES",
                "nome" => "Espírito Santo",
            ],
            [
                "id" => 33,
                "sigla" => "RJ",
                "nome" => "Rio de Janeiro",
            ],
            [
                "id" => 35,
                "sigla" => "SP",
                "nome" => "São Paulo",
            ],
            [
                "id" => 41,
                "sigla" => "PR",
                "nome" => "Paraná",
            ],
            [
                "id" => 42,
                "sigla" => "SC",
                "nome" => "Santa Catarina",
            ],
            [
                "id" => 43,
                "sigla" => "RS",
                "nome" => "Rio Grande do Sul",
            ],
            [
                "id" => 50,
                "sigla" => "MS",
                "nome" => "Mato Grosso do Sul",
            ],
            [
                "id" => 51,
                "sigla" => "MT",
                "nome" => "Mato Grosso",
            ],
            [
                "id" => 52,
                "sigla" => "GO",
                "nome" => "Goiás",
            ],
            [
                "id" => 53,
                "sigla" => "DF",
                "nome" => "Distrito Federal",
            ],
        ];

        Estado::insert($estados);
    }
}
