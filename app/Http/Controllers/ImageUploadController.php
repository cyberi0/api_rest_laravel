<?php

namespace App\Http\Controllers;

use App\Models\ImagenUsuario;
use Illuminate\Http\Request;
use Storage;

class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,svg,gif|max:1024',
        ]);

        $imageName = $request->usuario_id.'-'.time().'.'.$request->image->extension();
        $upload = Storage::disk('s3')->put($imageName, file_get_contents($request->file('image')), 'public');
        $url = Storage::disk('s3')->url($imageName);

        if ($upload) {

            $ImagenUsuario = ImagenUsuario::updateOrCreate(
                ['usuario_id' => $request->usuario_id],
                ['url' => $url]
            );

            return response()->json(
                [ 'success' => true,
                    'message' => 'Imagen Subida en S3 Bucket',
                    'url' => $url,
                    'imagenUsuarioData'=>$ImagenUsuario
                ], 200);

        } else {
            return response()->json(
                [ 'success' => true,
                    'message' => 'Error al Subir Imagen en S3 Bucket',
                    'url' => false,
                    'imagenUsuarioData'=>false
                ], 400);

        }

    }
}
