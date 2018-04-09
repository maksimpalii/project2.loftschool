<?php

namespace App;

use Intervention\Image\ImageManagerStatic as Image;

class File
{
    public function uploadImg($fileUpload, $login)
    {
        $file = $fileUpload['image'];
        $image = Image::make($fileUpload['image']['tmp_name'])
            ->resize(100, 100)
            ->save('photos/' . $login . '_' . $file['name']);
        return $login . '_' . $file['name'];
    }
}
