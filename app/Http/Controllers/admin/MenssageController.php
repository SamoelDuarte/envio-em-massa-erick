<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Customer;
use App\Models\ImagemEmMassa;
use App\Models\Messagen;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SplFileObject;
use Yajra\DataTables\Facades\DataTables;

class MenssageController extends Controller
{



    public function index()
    {
        return view('admin.message.index');
    }
    public function getMessage()
    {
        $messagens = Messagen::with('device')->orderBy('id')->get();
        return DataTables::of($messagens)->make(true);
    }
    public function upload(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Define as regras de validação para a imagem
        ]);

        // Salvar a imagem no diretório de armazenamento
        $imagemNome = time() . '.' . $request->imagem->extension();
        $request->imagem->move(public_path('imagens'), $imagemNome);

        // Salvar o caminho da imagem no banco de dados
        $caminho = 'imagens/' . $imagemNome;
        ImagemEmMassa::create(['caminho' => $caminho]);

        return redirect()->back()->with('success', 'Imagem enviada com sucesso.');
    }
    public function create()
    {
        $imagens = ImagemEmMassa::all();

        // Passar as imagens para a visão
        return view('admin.message.create', compact('imagens'));
    }
    public function bulkMessage(Request $request)
    {

        if ($request->texto == "") {
            return back()->with('error', 'Mensagem não pode estár Vazia');
        }
        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');

            $handle = new SplFileObject($file->getPathname(), 'r');

            foreach ($handle as $linha) {
                $mensagen = new Messagen();
                $mensagen->messagem =  $request->texto;
                $mensagen->image_id =  $request->imagem_id;
                $mensagen->number = $this->formatarTexto($linha);
                $mensagen->save();
            }
        }
        return Redirect::route('admin.message.index')->with('success', 'Mensagem Salva Com Sucesso');
    }

    // public function indexAgendamentos(){
    //     $agendamentos = Agendamento::all();

    //     return view('admin.message.agendamentos' , compact('agendamentos'));
    // }
    // public function getAgendamentos(){
    //     $agendamento = Agendamento::orderBy('id', 'desc');
    //     return DataTables::of($agendamento)->make(true);
    // }


    public function formatarTexto($texto)
    {
        // Remover os caracteres (.-+) e espaços
        $textoFormatado = preg_replace('/[.\-+\s]+/', '', $texto);

        // Se o texto limpo tiver exatamente 11 caracteres, concatenar '55' no início
        if (strlen($textoFormatado) === 11) {
            $textoFormatado = '55' . $textoFormatado;
            return $textoFormatado;
        }
        
        return false;

      
    }


    public function countContact(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');
            $totalLinhas = 0;

            $handle = fopen($file->getPathname(), 'r');
            while (!feof($handle)) {
                $linha = fgets($handle);

                // Formatar o texto antes de contar
                $textoFormatado = $this->formatarTexto($linha);
                if ($textoFormatado !== false) {
                    $totalLinhas++;
                }
            }
            fclose($handle);

            return response()->json(['totalLinhas' => $totalLinhas]);
        }

        return response()->json(['totalLinhas' => 0]);
    }
}
