<?php namespace App\Services\Api;

use \App\Services\Image\Processing as ImageProcessing;
use \App\Services\Image\Repository as ImageRepository;
use \App\Services\Image\ModelFactory as ImageModelFactory;

use \Symfony\Component\HttpFoundation\File\UploadedFile;

use \App\Models\Image as ImageModel;
use \App\Models\ResizedImage as ResizedImageModel;
use \App\Models\User;

abstract class AbstractApi
{

    static $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/gif'];

    /**
     * @param \App\Models\User $user
     * @return array
     */
    public function getListOfImagesByUser(User $user)
    {
        $images = [];

        foreach($user->images()->get() as $image)
        {
            $images[$image->image_name]['link'] = ImageModel::getLink($image->image_name);
            $images[$image->image_name]['width'] = $image->width;
            $images[$image->image_name]['height'] = $image->height;
            $image->resizedImages()->get();

            foreach($image->resizedImages()->get() as $resizedImage)
            {
                $images[$image->image_name]['resized'][$resizedImage->image_name]['link'] = ResizedImageModel::getLink($resizedImage->image_name);
                $images[$image->image_name]['resized'][$resizedImage->image_name]['width'] = $resizedImage->width;
                $images[$image->image_name]['resized'][$resizedImage->image_name]['height'] = $resizedImage->height;
            }
        }

        return $images;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param User $user
     * @param $newWidth
     * @param $newHeight
     * @return array
     */
    public function resizeNewImageWithData(UploadedFile $file, User $user, $newWidth, $newHeight)
    {
        $filename = ImageRepository::saveImageFile($file, $user->id);
        $imageSizes = ImageProcessing::getSizesByImageName($filename, ImageProcessing::ORIGINAL_IMAGE_TYPE);
        $imageModel = ImageModelFactory::makeUploadedImageModel($filename, $user->id, $imageSizes['width'], $imageSizes['height']);
        $imageModel->save();

        $resizedFilename = ImageProcessing::resizeImageByFilename($imageModel->image_name, $newWidth, $newHeight);
        $resizedImageModel = ImageModelFactory::makeResizedImageModel($imageModel, $resizedFilename, $newWidth, $newHeight, $user->id);
        $resizedImageModel->save();

        $link = ResizedImageModel::getLink($resizedImageModel->image_name);
        $data = [
            'status' => 'success',
            'data' =>
                [
                    'link'      => $link,
                    'width'     => $newWidth,
                    'height'    => $newHeight
                ]
        ];

        return $data;
    }

    /**
     * @param \App\Models\Image $imageModel
     * @param User $user
     * @param $newWidth
     * @param $newHeight
     * @return array
     */
    public function resizeOldImageWithData(ImageModel $imageModel, User $user, $newWidth, $newHeight)
    {
        $resizedFilename = ImageProcessing::resizeImageByFilename($imageModel->image_name, $newWidth, $newHeight);
        $resizedImageModel = ImageModelFactory::makeResizedImageModel($imageModel, $resizedFilename, $newWidth, $newHeight, $user->id);
        $resizedImageModel->save();

        $link = ResizedImageModel::getLink($resizedImageModel->image_name);

        $data = [
            'status' => 'success',
            'data' =>
                [
                    'link' => $link,
                    'width' => $newWidth,
                    'height' => $newHeight
                ]
        ];

        return $data;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return bool
     */
    public static function checkMimeType(UploadedFile $file)
    {
        if(in_array($file->getMimeType(), self::$allowedMimeTypes)) {
            return true;
        } else {
            return false;
        }
    }


}