<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $table = 'tests';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dataFormat = 'yyyy-mm-dd h:m:s';
    protected $fillable = [
        'msg',
    ];
}
