<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $rotas = Route::with('colaborador')->get();
        return view('admin.route.index', compact('rotas'));
    }
    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $request->validate([
            'nomeRota' => 'required|string|max:255',
        ]);

        // Crie uma nova instância do modelo Route
        $novaRota = new Route();
        $novaRota->name = $request->input('nomeRota');
        $novaRota->save();

        // Se necessário, você pode retornar uma resposta JSON ou redirecionar para outra página
        return response()->json(['message' => 'Rota salva com sucesso']);
    }

    public function delete(Request $request)
    {
        $rotaId = $request->input('id');

        // Verifique se a rota existe
        $rota = Route::find($rotaId);

        if (!$rota) {
            return response()->json(['error' => 'Rota não encontrada'], 404);
        }

        // Realize a exclusão
        $rota->delete();

        // Resposta de sucesso
        return response()->json(['message' => 'Rota deletada com sucesso']);
    }

    public function adicionarColaborador(Request $request)
    {



        $rota = Route::find($request->rotaId);

        // Adicionar o colaborador à rota
        $rota->colaborador_id = $request->colaboradorId;
        $rota->save();

        return response()->json(['message' => 'Colaborador adicionado à rota com sucesso.']);
    }

    public function edit(Request $request)
    {
        // dd($request->all());
        // Encontre a rota pelo ID
        $rota = Route::findOrFail($request->idRotaEdit);

        $rota->name = $request->nomeRotaEdit;
        $rota->save();



     

        // Retorne a view do formulário de edição com os detalhes da rota
        return back()->with(['success' => 'Atualizado com Sucesso']);
    }
}
