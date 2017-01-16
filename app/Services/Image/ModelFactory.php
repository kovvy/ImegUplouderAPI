<?php namespace App\Services\Image;

use \App\Models\Image as ImageModel;
use \App\Models\ResizedImage as ResizedImageModel;

class ModelFactory {

    /**
     * @param $filename
     * @param $userId
     * @param $width
     * @param $height
     * @return ImageModel|\Illuminate\Support\Collection|null|static
     */
    static function makeUploadedImageModel($filename, $userId, $width, $height)
    {
        $checkImage = ImageModel::find($filename);
        if($checkImage instanceof ImageModel) {
            return $checkImage;
        }

        $imageModel = new ImageModel();
        $imageModel->image_name = $filename;
        $imageModel->width = $width;
        $imageModel->height = $height;
        $imageModel->user_id = $userId;

        return $imageModel;
    }

    /**
     * @param ImageModel $originalImageModel
     * @param $resizedImageFileName
     * @param $width
     * @param $height
     * @param $userId
     * @return ResizedImageModel|\Illuminate\Support\Collection|null|static
     */
    static function makeResizedImageModel(ImageModel $originalImageModel, $resizedImageFileName, $width, $height, $userId)
    {
        $checkResizedImage = ResizedImageModel::find($resizedImageFileName);
        if($checkResizedImage instanceof ResizedImageModel) {
            return $checkResizedImage;
        }

        $resizedImageModel = new ResizedImageModel();
        $resizedImageModel->image_name = $resizedImageFileName;
        $resizedImageModel->parent_image_name = $originalImageModel->image_name;
        $resizedImageModel->width = $width;
        $resizedImageModel->height = $height;
        $resizedImageModel->user_id = $userId;

        return $resizedImageModel;
    }


}