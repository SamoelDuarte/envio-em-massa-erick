<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messagen extends Model
{

    
    use HasFactory;
    protected $table = 'messagens';
    protected $appends = [
        'display_status',
        'display_created_at',
        'image_id'
    ];
   

    public function device()
    {
        
        return $this->belongsTo(Device::class);
    }

    public function imagem()
    {
        return $this->belongsTo(ImagemEmMassa::class, 'image_id');
    }

    public function getDisplayStatusAttribute()
    {
        $status = $this->device_id;

        if($status == null){
            $status = "Pendente";
        }else{
            $status = "Enviado";
        }

        return $status;
    }

    public function getDisplayCreatedAtAttribute()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

}
