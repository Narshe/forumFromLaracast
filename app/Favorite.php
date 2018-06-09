<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'favorited_id', 'favorited_type', 'create_at', 'updated_at'];


    /**
     * [favorited]
     * @return MorphTo favirite
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
