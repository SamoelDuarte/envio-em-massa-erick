<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Campaign;
use App\Models\Chat;
use App\Models\Colaborador;
use App\Models\Customer;
use App\Models\Device;
use App\Models\Messagen;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrdersItens;
use App\Models\Route;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{

    public function storeAvaliacao(Request $request)
    {
        //    dd($request->all());
        // Crie uma nova instância de Avaliacao
        $avaliacao = new Avaliacao();

        // Preencha os campos com os dados do formulário
        $avaliacao->nota = $request->input('rate');
        $avaliacao->comentario = $request->input('comentario');
        $avaliacao->telefone = $request->input('telefone');
        $avaliacao->ip_device = $request->input('ip_device');
        $avaliacao->colaborador_id = $request->input('colaborador_id');
        $avaliacao->nota = $request->input('nota');


        // Salve a avaliação no banco de dados
        $avaliacao->save();

        // Você pode retornar uma resposta ou redirecionar o usuário após salvar a avaliação
        return view("front.avaliacao.obrigado");
    }
    public function sendImage($session, $phone, $nomeImagen, $detalhes)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "image" => array(
                    "url" => $nomeImagen // public_path('uploads/' . $nomeImagen)
                ),
                "caption" => $detalhes
            ),
            "delay" => 3
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        //  file_put_contents(Utils::createCode() . ".txt", $response);

        curl_close($curl);
    }

    public function avaliacao(Request $request)
    {


        if ($request->name_rota) {

            // Buscar colaborador com base no colaborador_od associado à rota
            $rota = Route::where("name",  urldecode($request->name_rota))->first();




            if (!isset($rota->colaborador_id)) {
                echo json_encode(array("Mensagem" => "Sem Colaborador Vinculado"));
                exit;
            } else {
                $colaborador = Colaborador::find($rota->colaborador_id);
                return view("front.avaliacao.index", compact('colaborador'));
            }
        }

        $colaborador = Colaborador::find($request->colaborador);

        if (!$colaborador) {
            echo json_encode(array("Mensagem" => "Sem Colaborador Vinculado"));
            exit;
        } else {
            return view("front.avaliacao.index", compact('colaborador'));
        }
    }

    public function index()
    {
        $reponseJson = file_get_contents('php://input');

        // file_put_contents(Utils::createCode()."-audio.txt",$reponseJson);
        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();

        if ($reponseArray['data']['event'] == "DISCONNECTED") {
            $session->status = "DISCONNECTED";
            $session->update();
            exit;
        }

        // verifica se o serviço está em andamento

        $active = 1;
        if ($active) {

            $this->verifyService($reponseArray, $session);
        }

        //  file_put_contents(Utils::createCode().".txt",$reponseJson);
    }

    public function teste()
    {
        $texto = file_get_contents('php://input');
        $reponseJson = file_get_contents('teste.txt');

        $reponseArray = json_decode($reponseJson, true);
        $session = Device::where('session', $reponseArray['data']['sessionId'])->first();

        //  dd($reponseArray['data']['sessionId']);


        // verifica se o serviço está em andamento
        $this->verifyService($reponseArray, $session);
    }

    public function mensagemEmMassa()
    {
        $devices = Device::get(); // IDs dos dispositivos
        // Configurar o Carbon para usar o fuso horário de São Paulo
        $now = Carbon::now('America/Sao_Paulo');

        $daysOfWeek = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sábado',
        ];

        $dayOfWeek =  $daysOfWeek[$now->dayOfWeek];
        // Obter a hora e minutos atuais
        $currentTime = $now->format('H:i:s');

        // Verifique se existe um slot disponível com os parâmetros fornecidos
        $exists = DB::table('available_slots')
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->exists();

        if (!$exists) {
            print_r('Fora de Data de Agendamento' . $currentTime);
            exit;
        }

        // Obter campanhas ativas
        $campaigns = Campaign::where('status', 'play')->with(['contactList' => function ($query) {
            $query->wherePivot('send', false);
        }])->get();


        foreach ($campaigns as $campaign) {
            foreach ($devices as $device) {
            
                // Verifica se o número de mensagens enviadas nas últimas horas é menor ou igual a 39
                $messageCount = $device->message_count_last_hour;
               
                if ($messageCount <= 39) {
                    foreach ($campaign->contactList as $contactList) {
                        if ($contactList->phone != "" ) {
                            $imagen = asset($campaign->imagem->caminho);
                            $texto = "";
                            if($campaign->texto != null){
                                $texto = $campaign->texto;
                            }
                            $this->sendImage($device->session, $contactList->phone, $imagen, $texto);

                            // Marcar como enviado
                            $contactList->pivot->send = true;
                            $contactList->pivot->save();

                            echo 'enviado : ' . $contactList->phone . ' <br>';
                        }
                    }
                }
            }
        }
    }

    public function verifyService($reponseArray, $session)
    {
        if ($reponseArray['data']['message']['fromMe']) {
            // exit;
        }
        if (!$reponseArray['data']['message']['fromMe'] || !$reponseArray['data']['message']['fromGroup']) {



            $jid = $reponseArray['data']['message']['from'];

            $service = Chat::where('session_id',  $session->id)
                ->where('jid', $jid)
                ->where('active', 1)
                ->first();


            $customer = Customer::where('jid',  $jid)
                ->first();




            if (!$service) {

                $service = new Chat();
                $service->jid = $jid;
                $service->session_id = $session->id;
                $service->service_id = Utils::createCode();
                $service->save();
            }

            if (!$customer) {
                $customer = new Customer();
                $customer->jid = $jid;
                $customer->save();
                if ($reponseArray['data']['message']['type'] == "audio") {
                    $service->await_answer = "await_human";
                    $service->update();
                    exit;
                }


                $text = 'Olá! 🌟 Antes de continuarmos, poderia, por favor, nos fornecer o seu nome?\nEstou aqui para ajudar e tornar o atendimento mais pessoal. 😊💬';
                $service->await_answer = "name";
                $service->save();
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            }


            if ($customer && $service->await_answer == null) {

                if ($reponseArray['data']['message']['type'] == "audio") {
                    $service->await_answer = "await_human";
                    $service->update();
                    exit;
                }

                if ($service->await_answe == "await_human" || $service->await_answe == "in_service") {
                    exit;
                }
                $service->await_answer = "init_chat";
            }
            //dd($service);




            if ($service->await_answer == "name") {
                $customer->name = $reponseArray['data']['message']['text'];
                $customer->update();
                $text = "Por favor " . $customer->name . " Digite seu Cep";
                $service->await_answer = "cep";
                $service->update();
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            }



            if ($service->await_answer == "cep") {

                $cep = $reponseArray['data']['message']['text'];
                $cep = Utils::returnCep($cep);
                if ($cep) {
                    $customer->zipcode = $cep['cep'];
                    $customer->public_place = $cep['logradouro'];
                    $customer->neighborhood = $cep['bairro'];
                    $customer->city = $cep['localidade'];
                    $customer->state = $cep['uf'];
                    $customer->update();
                    $service->await_answer = "number";
                    $service->update();
                    $text = "Por Favor Digite o Número da residência";
                } else {
                    $service->await_answer = "cep";
                    $text = "Cep inválido Digite novamente!";
                }
                $this->sendMessagem($session->session, $customer->phone, $text);
                exit;
            }


            if ($service->await_answer == "number") {

                $customer->number = $reponseArray['data']['message']['text'];
                $customer->update();
                $location = $customer->location . " \n  O Endereço está Correto ? ";
                $options = [
                    "Sim",
                    "Não"
                ];
                $this->sendMessagewithOption($session->session, $customer->phone, $location, $options);

                $service->await_answer = "cep_confirmation";
                $service->update();
            }



            if ($service->await_answer == "cep_confirmation") {

                $response = $reponseArray['data']['message']['text'];

                switch ($response) {
                    case  "1";
                        $service->await_answer = "init_chat_1";
                        $service->update();
                        $text =  $customer->name . " \n  Seu cadastro foi Realizado \n com sucesso ";
                        $this->sendMessagem($session->session, $customer->phone, $text);

                        $text = "Por favor " . $customer->name . " Selecione uma das Opções .";
                        $options = [
                            "Novo Pedido",
                            "Falar com um Atendente."
                        ];
                        $this->sendMessagewithOption($session->session, $customer->phone, $text, $options);
                        exit;
                        break;

                    case '2';
                        $service->await_answer = "cep";
                        $service->update();
                        $text =  $customer->name . " \n Por favor Digite seu cep Novamente.";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        exit;
                        break;

                    default:
                        $service->erro =  $service->erro + 1;
                        $service->update();
                        $text =  "Opção inválida!";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        if ($service->erro > 2) {
                            $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                            $this->sendMessagem($session->session, $customer->phone, $text);
                            $service->await_answer = "await_human";
                            $service->update();
                        }


                        break;
                }
            }


            if ($service->await_answer == "init_chat") {


                $text = "Olá " . $customer->name . " é bom ter voçê novamente aki! ";
                $this->sendMessagem($session->session, $customer->phone, $text);

                $service->await_answer = "init_chat_1";
                $service->update();
                $text = "Por favor " . $customer->name . " Selecione uma das Opções .";
                $options = [
                    "Novo Pedido",
                    "Falar com um Atendente."
                ];
                $this->sendMessagewithOption($session->session, $customer->phone, $text, $options);
                exit;
            }

            if ($service->await_answer == "init_chat_1") {
                $response = $reponseArray['data']['message']['text'];

                switch ($response) {
                    case  "1";
                        $service->await_answer = "init_order";
                        $service->update();
                        $text = "Por favor Selecione uma das Opções .";
                        $options = [
                            "13kg R$ 99,99",
                            "20kg R$ 140,00"
                        ];
                        $this->sendMessagewithOption($session->session, $customer->phone, $text, $options);
                        exit;
                        break;

                    case '2';
                        $service->await_answer = "await_human";
                        $service->update();
                        $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                        $this->sendMessagem($session->session, $customer->phone, $text);

                        break;


                    default:
                        $service->erro =  $service->erro + 1;
                        $service->update();
                        $text =  "Opção inválida!";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        if ($service->erro > 2) {
                            $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                            $this->sendMessagem($session->session, $customer->phone, $text);
                            $service->await_answer = "await_human";
                            $service->update();
                        }

                        break;
                }
            }
            if ($service->await_answer == "init_order") {
                $response = $reponseArray['data']['message']['text'];
                $order = new Order();
                $order->status = "opened";
                $order->customer_id = $customer->id;
                $order->save();
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;

                if ($response == '1') {
                    $orderItem->price = "99.00";
                }
                if ($response == '2') {
                    $orderItem->price = "140.00";
                }
                if ($response != "1" && $response != "2") {

                    $service->erro =  $service->erro + 1;
                    $service->update();
                    $text =  "Opção inválida!";
                    $this->sendMessagem($session->session, $customer->phone, $text);
                    if ($service->erro > 2) {
                        $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        $service->await_answer = "await_human";
                        $service->update();
                    }
                }


                $orderItem->save();
                $service->await_answer = "question_closes";
                $service->update();
                $text = "Por favor Selecione uma das Opções .";
                $options = [
                    "Finalizar Pedido",
                    "Continuar Comprando"
                ];
                $this->sendMessagewithOption($session->session, $customer->phone, $text, $options);
                exit;
            }

            if ($service->await_answer == "question_closes") {
                $response = $reponseArray['data']['message']['text'];

                if ($response == '1') {

                    $order = Order::where('customer_id', $customer->id)
                        ->where("status", "opened")->orderByDesc('id')->first();

                    $orderItens = $order->orderItens->first();

                    $text = "Por favor verifique o pedido \n  Total :" . $orderItens->price . " \n"
                        . " Endereço  \n" . $customer->location . " \n Os dados do pedido estão correto ?";
                    $options = [
                        "Sim",
                        "Não"
                    ];
                    $service->await_answer = "finish";

                    $service->update();
                    $this->sendMessagewithOption($session->session, $customer->phone, $text, $options);
                    exit;
                }
                if ($response == '2') {
                    $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                    $this->sendMessagem($session->session, $customer->phone, $text);
                    $service->await_answer = "await_human";
                    $service->update();
                }

                if ($response != "1" && $response != "2") {

                    $service->erro =  $service->erro + 1;
                    $service->update();
                    $text =  "Opção inválida!";
                    $this->sendMessagem($session->session, $customer->phone, $text);
                    if ($service->erro > 2) {
                        $text =  "Por favor aguarde ,em instantes voçê será atendido(a).";
                        $this->sendMessagem($session->session, $customer->phone, $text);
                        $service->await_answer = "await_human";
                        $service->update();
                    }
                }
            }

            if ($service->await_answer == "finish") {
                date_default_timezone_set('America/Sao_Paulo');
                $horaAtual = Carbon::now();
                $horaMais45Minutos = $horaAtual->addMinutes(45);
                $text = " Pedido feito com Sucesso .";
                $this->sendMessagem($session->session, $customer->phone, $text);

                $text = "Previsão da entrega " . $horaMais45Minutos->format('H:i');
                $this->sendMessagem($session->session, $customer->phone, $text);

                $text = "Muito Obrigado! ";
                $this->sendMessagem($session->session, $customer->phone, $text);
                $service->active = 0;
                $service->update();
            }
        }
    }

    public function sendMessagem($session, $phone, $texto)
    {


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "number": "' . $phone . '",
                                        "message": {
                                            "text": "' . $texto . '"
                                        },
                                        "delay": 3
                                    }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }

    public function sendMessagewithOption($session, $phone, $text, $options)
    {
        $curl = curl_init();

        $send = array(
            "number" => $phone,
            "message" => array(
                "text" => $text,
                "options" => $options,
            ),
            "delay" => 3
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($send),
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function sendAudio($session, $phone)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('APP_URL_ZAP') . '/' . $session . '/messages/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "number": "' . $phone . '",
            "message": {
                "audio": {
                    "url" : "http://localhost:3333/static/audio/2F49EE65082AB66116EBFC03DC26C44D.ogg?sessionId=JOSE_1&messageId=2F49EE65082AB66116EBFC03DC26C44D"
                }
            },
            "delay": 0
        }',
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}
