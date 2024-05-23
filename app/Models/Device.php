<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $appends = [
        'display_status',
        'message_count_last_hour',
    ];
    protected $fillable = [
        'name',
        'picture',
        'jid',
        'session',
        'status',

    ];

    // Método para contar mensagens enviadas pelo dispositivo nas últimas horas
    public function getMessageCountLastHourAttribute()
    {
        // Data e hora atual
        $now = Carbon::now();

        // Subtrai uma hora da data e hora atual para obter a hora anterior
        $oneHourAgo = $now->subHour();

        // Contagem das mensagens enviadas pelo dispositivo nas últimas horas
        return $this->messages()
            ->where('device_id', $this->id) // Somente mensagens relacionadas a este dispositivo
            ->where('created_at', '>=', $oneHourAgo)
            ->count();
    }

    // Relacionamento um-para-muitos com Message
    public function messages()
    {
        return $this->hasMany(Messagen::class, 'device_id');
    }

    public function getDisplayStatusAttribute()
    {

        if ($this->status == "AUTHENTICATED") {
            return "Conectado";
        }

        if ($this->status == "DISCONNECTED") {
            return "Desconectado";
        }
    }
}
