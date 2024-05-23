<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Deliverymen extends Model
{
    protected $table = 'deliverymens'; // Especifica o nome da tabela
    protected $fillable = ['name', 'image','image_url','deleted']; // Specify which fields can be mass assigned

    // Other model definitions, such as relationships, can be added here

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url('deliveryman_images/' . $this->image);
        } else {
            // Caso não haja imagem, retorne uma imagem padrão ou null, conforme necessário
            return null;
        }
    }

    public function forms()
    {
        return $this->hasMany(Form::class, 'deliveryman_id');
    }
}