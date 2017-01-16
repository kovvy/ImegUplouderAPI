<?php namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;

class Image extends Eloquent
{
    const PATH = '/storage/img/original/';

    protected $connection = 'mongodb';

    protected $collection = 'images';

    protected $primaryKey = 'image_name';

    protected $fillable = ['user_id', 'image_name', 'width', 'height'];

    protected $user_id;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resizedImages() {
        return $this->hasMany(ResizedImage::class, 'parent_image_name', 'image_name');
    }

    /**
     * @param $imageName
     * @return string
     */
    static function getLink($imageName)
    {
        $link = 'http://' . env('APP_HOST') . self::PATH . $imageName;

        return $link;
    }
}