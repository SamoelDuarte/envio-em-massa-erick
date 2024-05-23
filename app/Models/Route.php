<?php

// app/Models/Route.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $table = "routes";
    protected $fillable = ['name', 'colaborador_id'];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}
