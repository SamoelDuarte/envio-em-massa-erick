<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeviceController extends Controller
{


    public function dash()
    {

        return view('admin.dashboard.index');
    }
    public function index()
    {
        return view('admin.device.index');
    }
    public function create()
    {

        $devicesComStatusNull = Device::whereNull('status')->get();
        $devicesComStatusNull->each->delete();


        $device = new Device();

        $device->session = Utils::createCode();
        $device->save();


        $qrcodeImgSrc = $this->getQrCode($device->session);

        return view('admin.device.create', compact('device', 'qrcodeImgSrc'));
    }

    public function getDevices()
    {
        $devices = Device::orderBy('id');
        return DataTables::of($devices)->make(true);
    }

    public function updateStatus(Request $request)
    {
        $device = Device::where('id', $request->id)->first();



        $device->status = $request->status;
        $device->name = $request->name;
        $device->picture = $request->picture;
        $device->jid = $request->jid;
        $device->update();

        echo json_encode(array('status' => '1'));
    }

    public function updateName(Request $request)
    {
        $device = Device::where('id', $request->id)->first();
        $device->name = $request->name;
        $device->update();
        echo json_encode(array('status' => '1'));
    }

    function getQrCode($session)
    {

        // URL da requisição
        $url = env('APP_URL_ZAP').'/sessions/add';

        // Dados da requisição
        $data = array(
            'sessionId' => $session // Substitua $session pela sua variável contendo os dados
        );

        // Configuração da requisição
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
                'Content-Type: application/json'
            )
        );

        // Inicializar a sessão curl
        $ch = curl_init();

        // Configurar as opções do curl
        curl_setopt_array($ch, $options);

        // Executar a requisição e obter a resposta
        $response = curl_exec($ch);

        // Verificar se ocorreu algum erro
        if (curl_errno($ch)) {
            echo 'Erro na requisição: ' . curl_error($ch);
        }

        // Fechar a sessão curl
        curl_close($ch);

        // Tratar a resposta (no caso de JSON, decodificar o JSON)
        $result = json_decode($response, true);

        // Exemplo de utilização dos dados da resposta
        if (isset($result['qr'])) {
            return   $result['qr'];
            // Faça o que for necessário com a imagem do QR code
        }

        return false;
    }

    public function getStatus(Request $request)
    {
      

        $url = env('APP_URL_ZAP')."/sessions/" . $request->sessionId . "/status";

        $headers = array(
            'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição cURL: ' . curl_error($ch);
        }

        curl_close($ch);

        // A variável $response contém a resposta da requisição
        // Você pode processar os dados recebidos conforme necessário
        echo $response;
    }



    public function delete(Request $request){

      
        $device = Device::where('id',$request->id_device)->first();

        $device->delete();


        return back()->with('success','Deletado Com Sucesso.');


    }
}
