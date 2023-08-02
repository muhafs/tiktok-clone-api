<?php

namespace App\Services;

use Image;

class FileService
{
    public function updateImage($model, $request)
    {
        //?
        $image = Image::make($request->file('image'));

        //? Delete the old image if exists
        if (!empty($model->image)) {
            $currentImage = public_path() . $model->image;

            if (file_exists($currentImage) && $currentImage != public_path() . '/default.png') {
                unlink($currentImage);
            }
        }

        //? Extract the image
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        //? Crop the image
        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top,
        );

        //? Save the image
        $name = time() . '.' . $extension;
        $image->save(public_path() . '/files/' . $name);

        $model->image = '/files/' . $name;
        return $model;
    }
}
