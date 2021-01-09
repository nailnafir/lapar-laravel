<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'food_id',
        'user_id',
        'quantity',
        'total',
        'status',
        'payment_url'
    ];

    public function food() {
        return $this->hasOne(Food::class, 'id', 'food_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'double',
        'price' => 'integer',
        'quantity' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
    ];

    public function getCreatedAtAttribute($created_at) {
        return Carbon::parse($created_at)
            ->getPreciseTimestamp(3);
    }

    public function getUpdatedAtAttribute($updated_at) {
        return Carbon::parse($updated_at)
            ->getPreciseTimestamp(3);
    }
}
