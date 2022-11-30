<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListChat extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'list_chats';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dataFormat = 'yyyy-mm-dd h:m:s';
    protected $fillable = [
        'outgoing_id', 'incoming_id',
    ];
}
