<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'subtotal'];

    // Rincian ini milik 1 Transaksi induk
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Rincian ini merujuk pada 1 Produk spesifik
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}