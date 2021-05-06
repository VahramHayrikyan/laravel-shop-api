<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function createForm(){
//        dd(config('app.url'));
        $files = File::all();
        return view('test', [
            'files' => $files
        ]);
    }

    public function fileUpload(Request $req){
        $req->validate([
            'file' => 'required|mimes:jpg,png,jpeg|max:2048'
        ]);

        $fileModel = new File();

        if($req->file()) {
            $fileName = time().'_'.$req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');

            $fileModel->name = $req->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
                ->with('success','File has been uploaded.')
                ->with('file', $fileName);
        }
    }

    public function download($i)
    {
        $file = File::find($i);
//        $file = public_path(). '/' . $filePath;
        $file = public_path($file->file_path);
//        dd($file);

//        $headers = array(
//            'Content-Type: application/pdf',
//        );

        return response()->download($file);
//        return Response::download($file, 'filename.jpg');
    }
}
