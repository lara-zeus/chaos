<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraZeus\Chaos\Concerns\ChaosModel;

class Post extends Model
{
    use ChaosModel;
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'posts';
}
