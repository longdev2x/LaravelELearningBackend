<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;

    protected $casts = [
        'video' => 'json'
    ];

    public function setVideoAttribute($value)
    {
        /*
            'a' => 'value1',
            'b' => 'value2',
            'c' => 'value3',
            ...Convert to json
            {
                'a' : 'value1',
                'b' : 'value2',
                'c' : 'value3'
            }
        */
        $this->attributes['video'] = json_encode(array_values($value));
    }

    public function getVideoAttribute($value)
    {
        $resvideo = json_decode($value, true) ?: [];
        return $resvideo;
    }
}
