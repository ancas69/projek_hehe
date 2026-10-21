<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'topik_penelitian';
    public $timestamps = false;
}
