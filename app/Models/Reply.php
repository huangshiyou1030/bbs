<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reply extends Model
{
    use SoftDeletes;
    protected $fillable = ['content'];
    public function topic(){
        return $this->belongsTo(Topic::class);

    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
