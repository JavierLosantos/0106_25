<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserFile;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    public function index()
{
    $tenant = session('tenant');
    $users = $tenant->users()
    ->where('status', 'activo')
    ->where('role', 'Paciente')
    ->with('files')
    ->get();
  
    return view('users.index', compact('users'));
}


  /*  public function upload(Request $request, $userId)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpg,png,docx|max:2048'
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        UserFile::create([
            'user_id' => $userId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Archivo subido con éxito.');
    }
    
    */
    
public function upload(Request $request, $userId)
{   
  
    $request->validate([
    'file' => 'required|file'
]);

$allowedMimes = [
    'application/pdf',
    'image/jpeg',
    'image/png',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
];
    $file = $request->file('file');
    $fileName = time() . '_' . $file->getClientOriginalName();  



    // Ruta correcta en Hostinger
    $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
 
    // Mueve el archivo a public_html/uploads/
    $file->move($destinationPath, $fileName);
  
    // Guarda la ruta accesible en la base de datos
    UserFile::create([
        'user_id' => $userId,
        'file_name' => $file->getClientOriginalName(),
        'file_path' => 'uploads/' . $fileName,
    ]);

    return redirect()->back()->with('success', 'Archivo subido con éxito.');
}


public function deleteFile($fileId)
{
    $file = UserFile::findOrFail($fileId);
// Ruta correcta en Hostinger
   
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $file->file_path;
    // Ruta completa del archivo


    // Verificar si el archivo existe antes de eliminarlo
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Eliminar registro de la base de datos
    $file->delete();

    return back()->with('success', 'Archivo eliminado correctamente.');
}

}
