<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait MediaTrait
{
    public function uploadImage(UploadedFile $image, string $folder): string
    {
        $extension = $image->getClientOriginalExtension();
        $newFile = uniqid().'.'.$extension;
        $image->move(public_path("images/$folder"), $newFile);

        return $newFile;
    }
}
