<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileSasaran extends Model
{
    protected $primaryKey = 'id_profile';
    protected $table = 'profilesasaran';
    public $timestamps = false;
}
