<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $dataFormat = 'yyyy-mm-dd h:m:s';
    protected $fillable = [
        'outgoing_msg_id', 'incoming_msg_id', 'style', 'msg',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
