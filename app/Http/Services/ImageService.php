<?php

namespace App\Http\Services;

use Intervention\Image\ImageManagerStatic as Image;

class ImageService
{

    /**
     * Process the image upload
     *
     * @param $request
     * @param $requestValues
     * @return array
     */
    public function processImage($request, $requestValues): array
    {
        if ($request->hasFile('thumbnail')) {

            // create image name
            $filename = $this->createImageName($request);

            // Get the path to store the image
            $path = storage_path().'/app/public/images/collection-items/' . $filename;

            // Save the image
            Image::make($request->thumbnail->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path);

            // Overwrite thumbnail with the name ready to store in db
            $requestValues['thumbnail'] = $filename;

        }

        return $requestValues;
    }

    /**
     * Create image file name
     */
    public function createImageName($request): string
    {
        // Return the image name
        return $request->category_id . '-' . $request->file('thumbnail')->getClientOriginalName();
    }

    /**
     * Save a smaller version of the image keeping the aspect ratio
     */
    public function saveSmallImage($filename): void
    {
        // Get the original image path
        $path = storage_path().'/app/public/images/collection-items/' . $filename;

        // Get the path to store the small image
        $thumbnailPath = storage_path().'/app/public/images/collection-items/small/' . $filename;

        // Resize the image to a width of 300 and constrain aspect ratio (auto height)
        Image::make($path)->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailPath);

    }

    /**
     * Delete the image from storage
     *
     * @param $filename
     */
    public function deleteImage($filename): void
    {
        // Delete the image
        $path = storage_path().'/app/public/images/collection-items/' . $filename;
        if (file_exists($path)) {
            unlink($path);
        }

        // Delete the small image
        $thumbnailPath = storage_path().'/app/public/images/collection-items/small/' . $filename;
        if (file_exists($thumbnailPath)) {
            unlink($thumbnailPath);
        }
    }
}
