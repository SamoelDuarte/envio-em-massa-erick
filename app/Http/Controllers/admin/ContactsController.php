<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableSlot;
use App\Models\Contact;
use App\Models\ContactList;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SplFileObject;

class ContactsController extends Controller
{
    public function index()
    {
        $contacts = Contact::withCount('contactLists')->get();
        return view('admin.contact.index', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::with('contactLists')->findOrFail($id);
        return view('admin.contact.show', compact('contact'));
    }

    public function destroy($id)
    {
        $contactList = ContactList::findOrFail($id);
        $contactList->delete();

        return back()->with('success', 'Contato deletado com sucesso');
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'contact_id' => 'required|integer|exists:contacts,id',
        ]);

        $phoneFormater = $this->formatarTexto($request->phone);

        if ($phoneFormater) {
            $contactList = new ContactList();
            $contactList->phone = $phoneFormater;
            $contactList->contact_id = $request->contact_id;
            $contactList->save();

            return back()->with('success', 'Contato adicionado com sucesso');
        } else {
            return back()->with('error', 'Número de telefone inválido');
        }
    }

    public function storeFile(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file = $request->file('csvFile');
            $contactId = $request->input('contact_id');
            
            // Verifique se o contact_id está sendo recebido corretamente
            if (!$contactId) {
                return response()->json(['message' => 'ID do contato não fornecido'], 400);
            }
    
            // Tente buscar o contato
            $contact = Contact::find($contactId);
            
            if (!$contact) {
                return response()->json(['message' => 'Contato não encontrado'], 404);
            }
    
            $handle = new SplFileObject($file->getPathname(), 'r');
            $handle->seek(1);
    
            while (!$handle->eof()) {
                $linha = $handle->fgets();
                $linha = str_getcsv($linha);
    
                if (!empty($linha)) {
                    $phone = $linha[0];
                    $phoneFormater = $this->formatarTexto($phone);
                    if ($phoneFormater) {
                        $contactList = new ContactList();
                        $contactList->phone = $phoneFormater;
                        $contactList->contact_id = $contact->id;
                        $contactList->save();
                    }
                }
            }
    
            return response()->json(['message' => 'Contatos salvos com sucesso']);
        } else {
            return response()->json(['message' => 'Nenhum arquivo enviado'], 400);
        }
    }
    


    public function store(Request $request)
    {
        if ($request->name == "") {
            return back()->with('error', 'Mensagem não pode estár Vazia');
        }
        // Verificar se o arquivo CSV foi enviado
        if ($request->hasFile('csvFile')) {
            // Salvar o contato na tabela contacts
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->save();

            // Processar o arquivo CSV
            $file = $request->file('csvFile');
            $handle = new SplFileObject($file->getPathname(), 'r');

            // Ignorar a primeira linha (cabeçalho)
            $handle->seek(1);

            // Ler cada linha do arquivo CSV
            while (!$handle->eof()) {
                $linha = $handle->fgets();
                $linha = str_getcsv($linha);

                if (!empty($linha)) {
                    $phone = $linha[0];

                    // Salvar os dados na tabela contact_list
                    $phoneFormater = $this->formatarTexto($phone);
                    if ($phoneFormater) {
                        $contactList = new ContactList();
                        $contactList->phone = $phoneFormater;
                        $contactList->contact_id = $contact->id;
                        $contactList->save();
                    }
                }
            }
        } else {
            return back()->with('error', 'Escolha um arquivo CSV');
        }
        return Redirect::route('admin.contact.index')->with('success', 'Lista de contatos salva com sucesso');
    }
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
}
