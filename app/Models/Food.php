<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'picturePath', 'name', 'description', 'ingredients', 'location', 'price', 'discount', 'rate', 'types'
    ];

    public function toArray() {
        $toArray = parent::toArray();
        $toArray['picturePath'] = $this->picturePath;
        return $toArray;
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
    ];

    public function getCreatedAtAttribute($created_at) {
        return Carbon::parse($created_at)
            ->getPreciseTimestamp(3);
    }

    public function getUpdatedAtAttribute($updated_at) {
        return Carbon::parse($updated_at)
            ->getPreciseTimestamp(3);
    }

    public function getPicturePathAttribute() {
        return url('') . Storage::url($this->attributes['picturePath']);
    }
}
