<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColaboradorController extends Controller
{

    public function verAvaliacoes($id)
    {
        try {
            $colaborador = Colaborador::findOrFail($id);
            $avaliacoes = Avaliacao::where('colaborador_id', $id)->get();

            // Você pode retornar uma view com as avaliações ou manipular os dados conforme necessário
            return view('admin.colaborador.avaliacoes', compact('colaborador', 'avaliacoes'));
        } catch (\Exception $e) {
            // Trate o erro conforme necessário
            return response()->json(['error' => 'Erro ao obter avaliações do colaborador.'], 500);
        }
    }
    public function index()
    {
        $colaboradores = Colaborador::all();

        return view('admin.colaborador.index', compact('colaboradores'));
    }

    public function create(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'nome' => 'required|string|max:255',
            'imagem' => 'image|mimes:jpeg,png,jpg,gif|max:2048|required', // Verifique e ajuste os requisitos de sua aplicação
        ]);

        // Salvar a imagem
        $imagemPath = null;
        if ($request->hasFile('imagem')) {
            $imagemPath = $request->file('imagem')->store('imagens/colaboradores', 'public');
        }

        // Criar novo Colaborador
        $colaborador = new Colaborador();
        $colaborador->nome = $request->input('nome');
        $colaborador->imagem = $imagemPath;
        $colaborador->save();

        // Redirecionar para a página desejada após a criação do Colaborador
        return redirect()->route('admin.colaborador.index')->with('success', 'Colaborador criado com sucesso!');
    }

    public function edit($id)
    {
        try {
            // Encontrar o colaborador pelo ID
            $colaborador = Colaborador::findOrFail($id);

            // Construir a URL para a atualização do colaborador
            $url = route('admin.colaborador.update', $colaborador->id);

            // Retornar os detalhes do colaborador como JSON
            return response()->json([
                'success' => true,
                'imagem' => $colaborador->imagem,
                'nome' => $colaborador->nome,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            // Retornar uma mensagem de erro como JSON em caso de falha
            return response()->json(['success' => false, 'message' => 'Erro ao carregar detalhes do colaborador.' . $e->getMessage()]);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Encontrar o colaborador pelo ID
            $colaborador = Colaborador::findOrFail($id);

            // Validar os dados do formulário
            $request->validate([
                'nome' => 'required|string|max:255',
                'imagem' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Atualizar o nome do colaborador
            $colaborador->nome = $request->input('nome');

            // Atualizar a imagem se um novo arquivo for enviado
            if ($request->hasFile('imagem')) {
                // Remover a imagem antiga se existir
                if ($colaborador->imagem) {
                    Storage::disk('public')->delete($colaborador->imagem);
                }

                // Salvar a nova imagem
                $imagemPath = $request->file('imagem')->store('imagens/colaboradores', 'public');
                $colaborador->imagem = $imagemPath;
            }

            // Salvar as alterações
            $colaborador->save();


            return redirect()->route('admin.colaborador.index')->with('success', 'Colaborador Editado com sucesso!');
        } catch (\Exception $e) {
            // Retornar uma mensagem de erro em caso de falha
            return back()->with('error', 'Erro ao Atualizar');
        }
    }

    public function delete($id)
    {
        // Encontre o colaborador pelo ID
        $colaborador = Colaborador::findOrFail($id);

        // Excluir a foto se existir
        if ($colaborador->imagem) {
            // Storage::disk('public')->delete("imagens/colaboradores/{$colaborador->imagem}");
            Storage::disk('public')->delete($colaborador->imagem);
        }

        // Excluir o colaborador
        $colaborador->delete();
        return redirect()->route('admin.colaborador.index')->with('success', 'Colaborador Editado com sucesso!');
        // Resposta para o AJAX (você pode ajustar conforme necessário)
        return back()->with('success', 'Colaborador Deletado com sucesso!');
    }

    public function lista()
    {
        $colaboradores = Colaborador::select('id', 'nome')->get();

        return response()->json($colaboradores);
    }
    
}
