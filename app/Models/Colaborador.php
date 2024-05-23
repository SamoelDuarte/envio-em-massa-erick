<?php

// Em um arquivo de model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = "colaboradores";
    protected $fillable = ['nome', 'imagem'];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }

    public function getQuantidadeAvaliacoesAttribute()
    {
        return $this->avaliacoes->count();
    }

    public function getMediaEstrelasAttribute()
    {
        $avaliacoes = $this->avaliacoes;
        $totalAvaliacoes = $avaliacoes->count();

        if ($totalAvaliacoes > 0) {
            $somaEstrelas = $avaliacoes->sum('nota');
            return round($somaEstrelas / $totalAvaliacoes, 2);
        } else {
            return 0;
        }
    }
}

