<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function fileUpload()
    {
        $files = File::all();

        return view('file-upload', [
            'attachments' => $files
        ]);
    }

    public function upload(Request $req)
    {
        $req->validate([
            'file' => 'required|mimes:jpg,png,jpeg'
        ]);

//        dd($req->hasFile('file'));




        $fileModel = new File();
        $name = $req->file->getClientOriginalName();
        if($req->file()) {
            $fileName = Str::random(15).'_'.$name;
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

            $fileModel->name = $name;
            $fileModel->path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
                ->with('success','File has been uploaded.');
        }
    }

    public function download($id)
    {
        $file = File::find($id);

        $path = public_path($file->path);

        if ($file && file_exists($path)) {
            return response()->download($path);
        } else {
            return back()->withErrors(['file not found.', 'okok']);
        }
    }


}
