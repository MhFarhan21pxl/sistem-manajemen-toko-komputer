<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Relasi One-to-Many: 1 Kategori punya banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}