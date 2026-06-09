<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'transaction_code', 'total_price', 'money_paid', 'money_returned'];

    // Transaksi ini dilayani oleh 1 User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 Transaksi punya banyak rincian barang
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}