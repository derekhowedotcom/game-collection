<?php

namespace App\Http\Services;

use Intervention\Image\ImageManagerStatic as Image;

class CexService
{

    /**
     * Download the cex image from the url and save it to the public folder
     * @param $requestValues
     * @return array
     */
    public function downloadCexImage($requestValues): array
    {
        // Get the image from the url
        $image = file_get_contents($requestValues['cex_image']);

        // Get the path to store the image
        //$path = storage_path().'/app/public/images/collection-items/' . basename($requestValues['cex_image']);
        $path = public_path().'/storage/images/collection-items/' . basename($requestValues['cex_image']);

        // Save the image
        file_put_contents($path, $image);

        // Save the image
        Image::make($path)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path);

        // Overwrite thumbnail with the name ready to store in db
        $requestValues['thumbnail'] = basename($requestValues['cex_image']);

        return $requestValues;

    }

    /**
     * Save a smaller version of the cex image keeping the aspect ratio
     */
    public function saveSmallCexImage($filename): void
    {
        // Get the original image path
        //$path = storage_path().'/app/public/images/collection-items/' . $filename;
        $path = public_path().'/storage/images/collection-items/' . $filename;

        // Get the path to store the small image
        //$thumbnailPath = storage_path().'/app/public/images/collection-items/small/' . $filename;
        $thumbnailPath = public_path().'/storage/images/collection-items/small/' . $filename;

        // Resize the image to a width of 300 and constrain aspect ratio (auto height)
        Image::make($path)->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailPath);

    }


}
