<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_name',
        'product_description'
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }
}
