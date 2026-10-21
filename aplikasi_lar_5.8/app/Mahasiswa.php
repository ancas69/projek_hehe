<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    //
    protected $primaryKey = 'id_mahasiswa';
    protected $table = 'mahasiswa';
    public $timestamps = false;
}
