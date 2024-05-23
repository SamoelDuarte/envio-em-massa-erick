<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\MenuChatBot;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ChatBotController extends Controller
{
  public function index()
  {
    return view('admin.chatbot.index');
  }

  public function store(Request $request)
  {
    $menu = new MenuChatBot();

    dd($request->all());
  }
  public function getAtendimentoPedente()
  {
    $chats = Chat::where('await_answer', "await_human")->get();
   
    return $chats->count();
  }

  public function getChats(){
    $chats = Chat::with('customer')->orderByDesc('id');
    return DataTables::of($chats)->make(true);
  }

  public function up(Request $request){

    $chat = Chat::find($request->id_chat);

    

    $chat->await_answer = $request->status;
    if($request->status == 'finish'){
      $chat->active = 0;
    }
    $chat->update();

    return back()->with('success','Atualizado Com Sucesso.');
  }
}
