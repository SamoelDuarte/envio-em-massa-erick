<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Agendamento;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\Customer;
use App\Models\ImagemEmMassa;
use App\Models\Messagen;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use SplFileObject;
use Yajra\DataTables\Facades\DataTables;

class MenssageController extends Controller
{



    public function index()
    {
        $campaigns = Campaign::withCount('contact')
            ->with('contact')
            ->get()
            ->map(function ($campaign) {
                $campaign->total_to_send = $campaign->contact->count();
                $campaign->total_sent = 0; // Ajuste conforme necessário para obter o total enviado
                return $campaign;
            });
        return view('admin.message.index', compact('campaigns'));
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
        $contacts = Contact::withCount('contactLists')->get();
        return view('admin.message.create', compact('imagens', 'contacts'));
    }
    public function bulkMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!isset($request->contact_id)) {
            return redirect()->back()->with('error', 'Seleciono uma lista de Contato.')->withInput();
        }

        if (!isset($request->imagem_id)) {
            return redirect()->back()->with('error', 'Seleciono uma Imagens')->withInput();
        }

        $campaign = new Campaign();
        if ($request->texto != null) {
            $campaign->texto =  $request->texto;
        }
        $campaign->titulo =  $request->titulo;
        $campaign->contact_id = $request->contact_id;
        $campaign->imagem_id = $request->imagem_id;
        $campaign->status = 'play';
        $campaign->save();

        // Fetching contact lists associated with the given contact_id
        $contactLists = ContactList::where('contact_id', $request->contact_id)->get();

        // Saving relationships in the campaign_contact table
        foreach ($contactLists as $contactList) {
            $campaignContact = new CampaignContact();
            $campaignContact->campaign_id = $campaign->id;
            $campaignContact->contact_list_id = $contactList->id;
            $campaignContact->send = false; // Assuming default value is false
            $campaignContact->save();
        }


        return Redirect::route('admin.campaign.index')->with('success', 'Campanha Salva Com Sucesso');
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


        // Remover o prefixo 55 ou +55 se presente
        $textoFormatado = preg_replace('/^(55|\+55)/', '', $textoFormatado);

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
