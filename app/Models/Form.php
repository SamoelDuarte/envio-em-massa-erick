<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'deliveryman_id',
        'date',
        'maq',
        'total',
        'disk',
        'auto',
        'discount',
    ];

    public function deliveryman()
    {
        return $this->belongsTo(Deliverymen::class, 'deliveryman_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'form_product')->withPivot('value');
    }

    public function formProducts()
    {
        return $this->hasMany(FormProduct::class);
    }

    public function payments()
    {
        return $this->belongsToMany(PaymentMethod::class, 'form_payments')->withPivot('value');
    }

    public function getDisplayDateAttribute()
    {
        // Obtém a data do modelo
        $date = $this->attributes['date'];

        // Converte para um objeto Carbon
        $carbonDate = Carbon::createFromFormat('Y-m-d', $date);

        // Formata a data para o formato desejado
        return $carbonDate->format('d/m/Y');
    }
}
