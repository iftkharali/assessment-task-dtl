<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'products';

    protected $fillable = [
        'name', 'price', 'status', 'type'
    ];
    protected $casts = [
        'status' => ProductStatusEnum::class,
        'type' => ProductTypeEnum::class

    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($product) {
            $product->user_id = Auth::id();
        });
    }

    public function user(){
        return $this->belongsTo(User::class);

    }

}


