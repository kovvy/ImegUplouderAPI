<?php namespace App\Services\Image;

use \App\Models\Image as ImageModel;
use \App\Models\ResizedImage as ResizedImageModel;
use Intervention\Image\Facades\Image as Image;

class Processing {

    const ORIGINAL_IMAGE_TYPE = 1;
    const RESIZED_IMAGE_TYPE = 2;
    /**
     * @param $originalFileName
     * @param $newWidth
     * @param $newHeight
     * @return string
     */
    static function resizeImageByFilename($originalFileName, $newWidth, $newHeight)
    {
        $img = Image::make(base_path() . ImageModel::PATH . $originalFileName);
        $img->resize($newWidth, $newHeight);

        $resizedFileName = $newWidth . '_' . $newHeight . '_' . $originalFileName;
        $resizedFullPath = base_path() . ResizedImageModel::PATH . $resizedFileName;
        $img->save($resizedFullPath);

        return $resizedFileName;
    }

    /**
     * @param $imageName
     * @param int $type
     * @return array
     */
    static function getSizesByImageName($imageName, $type = self::ORIGINAL_IMAGE_TYPE)
    {
        switch($type) {
            case self::ORIGINAL_IMAGE_TYPE:
                $path = base_path() . ImageModel::PATH . $imageName;
                break;
            case self::RESIZED_IMAGE_TYPE:
                $path = base_path() . ResizedImageModel::PATH . $imageName;
                break;
            default:
                $path = base_path() . ImageModel::PATH . $imageName;
        }
        $img = Image::make($path);
        $sizes = [
            'width'     => $img->getWidth(),
            'height'    => $img->getHeight()
        ];

        return $sizes;
    }

}