<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TempImageExport;
use App\Http\Controllers\Controller;
use App\Models\Utilities\TempImage;
use File;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class TempImageController extends Controller
{
    public function index()
    {
        return view('backend.tempimages.index');
    }

    public function listAll()
    {
        return Excel::download(new TempImageExport, 'temp-images-' .  date('d-m-y') . '.xlsx');
    }

    public function deleteAll()
    {
        $images = TempImage::all();
        foreach ($images as $image) {
            Storage::disk('local')->delete($image->path);
            $image->delete();
        }

        flash(translate('Uploads deleted.'))->success();
        return back();
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $path = 'tempuploads/' . date('d-m-y') . '/';
                Storage::disk('local')->putFileAs($path, $file, $filename);
                // $file->storeAs($path, $filename);

                TempImage::create([
                    'name' =>  pathinfo($filename, PATHINFO_FILENAME),
                    'ext' => $extension,
                    'path' =>  $path . $filename
                ]);
            }
        }

        return redirect()->route('temp_image.all');
    }
}
