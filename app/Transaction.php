<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = "transactions";

    protected $fillable = [
        "trans_id", "total_amount",
        "product_code", "service_type",
        "user_id", "status"
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($query) {
            $query->status = "pending";
        });
    }

    public function customer() {
        return $this->belongsTo('App\User');
    }
}
