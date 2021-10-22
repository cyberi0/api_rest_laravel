<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function create(Request $request) {
        $input = $request->all();
        $validatedData = Validator::make($request->all(), $this->getRules(), $this->getMessages());

        if ($validatedData->fails()) {
            return response()->json([
                "response" => "error",
                "message" => $validatedData->errors()->first(),
                "code" => 400
            ]);
        }

        try {
            $input->password = Hash::make($input->password);
            #$input->email = Hash::make($input->password);
            $Usuario = Usuario::create($input);
            return response()->json([
                "response" => "success",
                "message" => "Usuario Registrado.",
                "data" => $this->getByID($Usuario->id)
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "response" => "error",
                    "message" => $e->getMessage()
                ]
            );
        }
    }

    public function getAll() {
        try {
            $Usuarios = Usuario::get();
            return response()->json([
                "response" => "success",
                "message" => "Lista de Usuarios Obtenida.",
                "data" => $Usuarios
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "response" => "error",
                    "message" => $e->getMessage()
                ]
            );
        }
    }

    public function getByID($id) {
        try{
            $Usuario = Usuario::find($id);

            if (is_null($Usuario)) {
                return response()->json([
                    "response" => "error",
                    "message" => "Usuario no Encontrado.",
                    "data" => $Usuario
                ]);
            } else {
                $Usuario = $Usuario
                    ->where('id', $id)
                    ->get();
            }

            return response()->json([
                "response" => "success",
                "message" => "Usuario Encontrado.",
                "data" => $Usuario
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "response" => "error",
                    "message" => $e->getMessage()
                ]
            );
        }
    }

    public function update(Request $request)
    {
        $validatedData = Validator::make($request->all(), $this->getRules(), $this->getMessages());
        if ($validatedData->fails()) {
            return response()->json([
                "response" => "error",
                "message" => $validatedData->errors()->first(),
                "code" => 400
            ]);
        }

        try {
            $input = $request->all();
            $Usuario = Usuario::findOrFail($request->id);

            $Usuario->fill($input)->save();

            return response()->json([
                "response" => "success",
                "message" => "Usuario actualizado.",
                "data" => $this->getByID($Usuario->id)
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "response" => "error",
                    "message" => $e->getMessage()
                ]
            );
        }
    }

    public function delete($id)
    {
        try {
            $Usuario = Usuario::find($id);


            if (!is_null($Usuario)) {
                $Usuario->destroy($id);
                return response()->json([
                    "response" => "success",
                    "message" => "Usuario Eliminado.",
                    "data" => $Usuario
                ]);
            } else {
                return response()->json([
                    "response" => "error",
                    "message" => "Usuario no Encontrado.",
                    "data" => $Usuario
                ]);
            }

        } catch (\Exception $e) {
            return response()->json(
                [
                    "response" => "error",
                    "message" => $e->getMessage()
                ]
            );
        }
    }


    public function getRules() {
        return [
            "nombre" => "required",
            "password" => "required",
            "fecha_nacimiento" => "required",
        ];
    }

    public function getMessages() {
        return [
            'nombre' => 'Debe escribir Nombre.',
            'password' => 'Debe escribir la Contraseña.',
            'fecha_nacimiento' => 'Ingresar Fecha de Nacimiento.',
        ];
    }
}
