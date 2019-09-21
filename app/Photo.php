<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 * @package App
 */
class Photo extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_photo');
    }

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return 'http://' . request()->getHttpHost() . '/images/' . $this->url;
    }
}
