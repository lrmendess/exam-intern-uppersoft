<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Usuario;
use App\Rules\TelefoneValidationRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
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
        return Usuario::with("endereco")->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Usuario::validate($request->all());

        if ($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try
        {
            // The transaction prevents a user from being created without an address or vice versa.
            // Maintaining the integrity of the database.
            DB::beginTransaction();
                $usuarioInput = Usuario::sanitize($request->except("endereco"));
                $usuario = Usuario::create($usuarioInput);
                
                $enderecoInput = array_merge($request->endereco, ["id" => $usuario->id]);
                Endereco::create($enderecoInput);
            DB::commit();

            return $usuario->load("endereco");
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
            return Usuario::with("endereco")->findOrFail($id);
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
        // Checks the integrity of the 1:1 relationship between Usuario and Endereco.
        // Also checks the integrity of unique fields.
        $customRules = [
            "id"          => ["exists:usuarios,id", Rule::in([$id])], // if specified
            "cpf"         => ["required", "min:11", "max:14", "cpf", "unique:usuarios,cpf,{$id}"],
            "email"       => ["required", "max:255", "email", "unique:usuarios,email,{$id}"],
            "endereco.id" => ["exists:usuarios,id", Rule::in([$id])], // if specified
        ];

        $validator = Usuario::validate($request->all(), $customRules);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $usuario = Usuario::with("endereco")->findOrFail($id);
            
            $usuarioInput = Usuario::sanitize($request->except("endereco"));
            
            $usuario->update($usuarioInput);
            $usuario->endereco()->update($request->endereco);

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
            // The address is also deleted because of the cascading deletion.
            Usuario::findOrFail($id)->delete();

            return response()->noContent();
        }
        catch (\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
