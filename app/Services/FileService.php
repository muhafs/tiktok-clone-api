<?php

namespace App\Services;

use Image;

class FileService
{
    public function updateImage($model, $request)
    {
        //? Create an Image Object from the Request
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

        //? Save it to database
        $model->image = '/files/' . $name;
        return $model;
    }

    public function addVideo($model, $request)
    {
        //? Extract the video
        $video = $request->file('video');
        $extension = $video->getClientOriginalExtension();

        //? Save the video
        $name = time() . '.' . $extension;
        $video->move(public_path() . '/files/' . $name);

        //? Save it to database
        $model->video = '/files/' . $name;
        return $model;
    }
}
