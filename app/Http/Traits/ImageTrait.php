<?php

namespace App\Http\Traits;

trait ImageTrait
{
    public function uploadImage($image, $filename, $folder, $oldImage = null)
    {
        $destinationPath = public_path('images/' . $folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $filename);
        return $filename;
    }

    public function uploadImg($image, $filename, $folder, $oldImage = null)
    {
        $destinationPath = public_path('images/' . $folder);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $filename);
        return 'images/' . $folder . '/' . $filename;
    }
}

