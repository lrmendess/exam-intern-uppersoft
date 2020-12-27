<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Estado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Usuario::with(["endereco", "endereco.estado"])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                "nome"                => "required | max:128 | string",
                "cpf"                 => "required | max:11 | cpf | unique:usuarios,cpf",
                "dataNascimento"      => "required | date | date_format:Y-m-d",
                "email"               => "required | max:255 | email | unique:usuarios,email",
                "telefone"            => "required | max:15",
                "endereco"            => "required",
                    "endereco.cidade"     => "required | max:64 | string",
                    "endereco.logradouro" => "required | max:128 | string",
                    "endereco.estado_id"  => "required | integer | exists:estados,id",
            ]
        );

        if ($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try
        {
            DB::beginTransaction();

            $endereco = Endereco::create($request->endereco);
            
            $data = array_merge($request->except("endereco"), ["endereco_id" => $endereco->id]);
            $usuario = Usuario::create($data);
            
            DB::commit();

            return $usuario;
        }
        catch (\Exception $e)
        {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try
        {
            return Usuario::with(["endereco", "endereco.estado"])->findOrFail($id);
        }
        catch (\Exception $e)
        {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try
        {
            $usuario = Usuario::findOrFail($id);

            // It works because of the cascade deletion. It is efficient, but not very intuitive.
            // A common solution would be to delete one entity at a time.
            $usuario->endereco()->delete();

            return response()->noContent();
        }
        catch (\Exception $e)
        {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
