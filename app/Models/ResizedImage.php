<?php namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ResizedImage extends Eloquent
{
    const PATH = '/storage/img/resized/';

    protected $connection = 'mongodb';

    protected $collection = 'resized_images';

    protected $primaryKey = 'image_name';

    protected $fillable = ['parent_image_name', 'user_id', 'image_name', 'width', 'height'];

    protected $parent_image_name;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentImage() {
        return $this->belongsTo(Image::class);
    }

    /**
     * @param $imageName
     * @param string $type
     * @return string
     */
    static function getLink($imageName)
    {
        $link = 'http://' . env('APP_HOST') . self::PATH . $imageName;

        return $link;
    }

}