<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $appends = [
        'sum_price_items',
        'display_status',
        'display_created_at'
    ];

    public function orderItens()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }


    public function getSumPriceItemsAttribute()
    {
        $price =  $this->items->where('order_id', $this->id)->sum('price');

        return "R$ " . number_format($price, 2, ",", ".");
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function getDisplayStatusAttribute(){
        
        if($this->status == "opened"){
            return "Aberto";
        }
    }

    public function getDisplayCreatedAtAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

}
