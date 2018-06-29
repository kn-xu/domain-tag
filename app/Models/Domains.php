<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    const FLAG_STATUS_UNKNOWN = 0;
    const FLAG_STATUS_SAFE = 1;
    const FLAG_STATUS_FLAGGED = 2;

    protected $table = 'domains';
}
