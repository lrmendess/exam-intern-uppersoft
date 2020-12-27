<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Usuario;
use App\Rules\TelefoneValidationRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Usuario::with(["endereco"])->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateUsuario($request->all());

        if ($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $usuarioData = $this->sanitizeUsuario($request->except("endereco"));
            $usuario = Usuario::create($usuarioData);
            
            $enderecoData = array_merge($request->endereco, ["usuario_id" => $usuario->id]);
            $endereco = Endereco::create($enderecoData);

            return $usuario->refresh();
        }
        catch (\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
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
            return Usuario::with(["endereco"])->findOrFail($id);
        }
        catch (\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_FOUND);
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
        /**
         * Checks if the (news?) cpf and email already exist.
         * Checks whether the user is the true owner of the address (if specified).
         */
        $overrideValidations = [
            "cpf"                 => ["required", "min:11", "max:14", "cpf", "unique:usuarios,cpf,{$id}"],
            "email"               => ["required", "max:255", "email", "unique:usuarios,email,{$id}"],
            "endereco.usuario_id" => ["exists:usuarios,id", Rule::in([$id])]
        ];

        $validator = $this->validateUsuario($request->all(), $overrideValidations);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $usuario = Usuario::with(["endereco"])->findOrFail($id);

            $usuarioData = $this->sanitizeUsuario($request->except("endereco"));
            $usuario->update($usuarioData);

            // An unexpected case where the user does not have an address.
            if (!$usuario->endereco()->exists())
            {
                $enderecoData = array_merge($request->endereco, ["usuario_id" => $usuario->id]);
                $endereco = Endereco::create($enderecoData);
            }
            else
            {
                $usuario->endereco()->update($request->endereco);
            }

            return $usuario->refresh();
        }
        catch (\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
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
            Usuario::findOrFail($id)->delete();

            return response()->noContent();
        }
        catch (\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Validate Usuario properties.
     * 
     * @param array $properties
     * @param array $overrideValidations
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateUsuario($properties, $overrideValidations = [])
    {
        $properties = $this->sanitizeUsuario($properties);

        $validations = [
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
        
        $validations = array_merge($validations, $overrideValidations);

        return validator()->make($properties, $validations);
    }

    /**
     * Sanitize Usuario properties.
     * 
     * @param array $properties
     * 
     * @return array (Usuario)
     */
    private function sanitizeUsuario($properties)
    {
        $properties["cpf"]      = preg_replace('/[^0-9]/', '', $properties["cpf"]);
        $properties["telefone"] = preg_replace('/[^0-9]/', '', $properties["telefone"]);

        return $properties;
    }
}
