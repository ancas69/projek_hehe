<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $primaryKey = 'id_dosen';
    protected $table = 'dosen';
    public $timestamps = false;
}
