<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Outras configurações e relacionamentos podem ser definidos aqui

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'form_payment')->withPivot('value');
    }
}
