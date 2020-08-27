<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = "transactions";

    protected $fillable = [
        "trans_id", "total_amount",
        "user_id", 
    ];

    public function customer() {
        // return $this->public function user()
        return $this->belongsTo('App\User');
    }
}
