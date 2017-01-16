<?php namespace App\Services\Image;

use \App\Models\Image as ImageModel;

class Repository {

    const IMAGES_STORAGE_ORIGINAL = '/storage/img/original/';

    static $imageFormats = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param $userId
     * @return \App\Models\Image|\Illuminate\Support\Collection|null|static
     */
    static function saveImageFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file, $userId)
    {
        $fileFormat = self::getFileFormatByMimeType($file);
        if($fileFormat) {
            $newFileName = md5_file($file->getRealPath()) . '_' . $userId . '.' . $fileFormat;

            $newFilePath = base_path() . ImageModel::PATH;
            $file->move($newFilePath, $newFileName);

            return $newFileName;
        }
        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return bool
     */
    static function getFileFormatByMimeType(\Symfony\Component\HttpFoundation\File\UploadedFile $file)
    {
        if(array_key_exists($file->getMimeType(),self::$imageFormats)) {
            return self::$imageFormats[$file->getMimeType()];
        } else {
            return false;
        }
    }
}