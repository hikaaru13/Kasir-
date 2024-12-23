<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'stock',
        'price',
        'variant',
        'category'
    ];

    public $timestamps = true;

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_id', 'product_id');
    }
}