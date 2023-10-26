<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'invoice_number',
      'product_id',
      'price',
      'quantity',
      'total',
      'transaction_date',
      'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactedProduct()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function canceledOrders()
    {
        return $this->hasOne(CanceledOrder::class, 'transaction_id', 'id');
    }
}
