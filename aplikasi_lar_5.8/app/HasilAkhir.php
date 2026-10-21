<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HasilAkhir extends Model
{
    protected $primaryKey = 'id_hasil';
    protected $table = 'hasilakhir';
    public $timestamps = false;
}
