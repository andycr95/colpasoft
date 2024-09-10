<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:2048',
        ]);
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName);
        $newFile = new File();
        $newFile->name = $fileName;
        $newFile->path = $filePath;
        $newFile->asset_id = $request->asset_id;
        $newFile->size = $file->getSize();
        $newFile->type = $file->getMimeType();
        $newFile->save();
        return response()->json(['success' => 'Archivo subido correctamente']);
    }

    //download file
    public function download($id)
    {
        $file = File::findOrFail($id);
        return response()->download(storage_path('app/' . $file->path));
    }
}
