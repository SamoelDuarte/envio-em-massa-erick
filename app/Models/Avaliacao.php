<?php

// Arquivo: app\Models\Avaliacao.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;
    protected $table = "avaliacoes";

    protected $fillable = ['colaborador_id', 'nota', 'comentario', 'telefone', 'ip_device'];

    public function colaborador()
    {
        return $this->belongsTo(Colaborador::class);
    }
}
