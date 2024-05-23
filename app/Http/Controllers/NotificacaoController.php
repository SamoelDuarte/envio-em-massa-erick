<?php

namespace App\Http\Controllers;

use App\Notifications\NovaNotificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function enviarNotificacao()
    {
        $mensagem = 'Nova notificação!';
        broadcast(new NovaNotificacao($mensagem));
        return response()->json(['message' => 'Notificação enviada']);
    }
}
