<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormProduct extends Model
{
    protected $table = 'form_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}