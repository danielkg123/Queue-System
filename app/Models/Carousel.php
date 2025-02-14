<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $table = 'carousel';

    protected $fillable = ['image_path', 'link', 'order', 'status'];
}
