<?php

namespace App\Http\Helpers;

use Storage;
use Illuminate\Http\File;

class UploadImage
{
    public static function upload($file, $path)
    {
        $fileName = $file->getClientOriginalName().'-'. rand(1, 999). time().'.'. $file->getClientOriginalExtension();

        $file->move('uploads/'. $path, $fileName);

        return 'uploads/'. $path . '/' .$fileName;
    }

    public static function uploadToStorage($file, $path)
    {
    	$fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .'-'. rand(1, 999). time().'.'. $file->getClientOriginalExtension();
        Storage::put($fileName, file_get_contents($file->getPathname()));

        $filePath = storage_path() . '/app/' . $fileName;

        return 'uploads/'. $path . '/' . $fileName;
    }

}
