<?php

namespace App\Http\Controllers;

use App\Models\Deliverymen;
use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeliverymenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.deliverymen.index');
    }

    public function info(Request $request, $id)
    {
        // Obtenha a data atual
        $currentDate = now();

        // Defina a data de início para o primeiro dia do mês atual
        $start_date = $currentDate->copy()->startOfMonth();

        // Defina a data de término como a data atual
        $end_date = $currentDate;

        // Obtenha os detalhes do motorista com o ID fornecido
        $motorista = Deliverymen::find($id);

        if (!$motorista) {
            // Retorne uma resposta de erro se o motorista não for encontrado
            return response()->json(['error' => 'Motorista não encontrado'], 404);
        }

        // Inicialize arrays para armazenar os dados do motorista
        $vendas = [];
        $values = [];
        $produtosVendidos = [];
        $sunValue = 0;
        $quantity = 0;

        // Consulte o banco de dados para obter as vendas do motorista no intervalo de datas fornecido
        $forms = Form::where('deliveryman_id', $motorista->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->with('formProducts')
            ->get();

        // Itere sobre os formulários e extraia as informações das vendas e produtos vendidos
        foreach ($forms as $form) {
            // Adicione as vendas deste formulário ao total de vendas
            $vendas[] = $form->disk + $form->auto;
            $quantity += $form->disk + $form->auto;

            // Adicione o valor total deste formulário ao array de valores
            $values[] = $form->total;

            // Itere sobre os produtos vendidos neste formulário
            foreach ($form->formProducts as $product) {
                // Calcule o valor médio do produto considerando o desconto
                $valorMedioProduto = (($product->value * $product->quantity) - $product->discount) / $product->quantity;

                // Adicione o valor total do produto ao valor total do motorista
                $sunValue += ($product->value * $product->quantity) - $product->discount;

                // Adicione o produto aos produtos vendidos
                $produtosVendidos[] = [
                    'id' => $product->id,
                    'name' => $product->product->name,
                    'quantidade' => $product->quantity,
                    'precoMedio' => sprintf("%.2f", $valorMedioProduto),
                ];
            }
        }

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'motorista' => [
                'id' => $motorista->id,
                'name' => $motorista->name,
                'image' => $motorista->image_url, // Você pode precisar ajustar isso se o nome do campo for diferente
                'sales' => $vendas,
                'values' => $values,
                'sunValue' => number_format($sunValue, 2, ',', '.'),
                'quantity' => $quantity,
                'produtos' => $produtosVendidos,
            ],
        ];

        return view('admin.deliverymen.info', $data);
    }

    public function getInfo(Request $request, $id)
    {
        // Obtenha as datas de início e fim da solicitação
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
    
        // Obtenha os detalhes do motorista com o ID fornecido
        $motorista = Deliverymen::find($id);
    
        if (!$motorista) {
            // Retorne uma resposta de erro se o motorista não for encontrado
            return response()->json(['error' => 'Motorista não encontrado'], 404);
        }
    
        // Inicialize arrays para armazenar os dados do motorista
        $vendas = [];
        $values = [];
        $produtosVendidos = [];
        $sunValue = 0;
        $quantity = 0;
    
        // Consulte o banco de dados para obter as vendas do motorista no intervalo de datas fornecido
        $forms = Form::where('deliveryman_id', $motorista->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->with('formProducts')
            ->get();
    
        // Itere sobre os formulários e extraia as informações das vendas e produtos vendidos
        foreach ($forms as $form) {
            // Adicione as vendas deste formulário ao total de vendas
            $vendas[] = $form->disk + $form->auto;
            $quantity += $form->disk + $form->auto;
    
            // Adicione o valor total deste formulário ao array de valores
            $values[] = $form->total;
    
            // Itere sobre os produtos vendidos neste formulário
            foreach ($form->formProducts as $product) {
                // Calcule o valor médio do produto considerando o desconto
                $valorMedioProduto = (($product->value * $product->quantity) - $product->discount) / $product->quantity;
    
                // Adicione o valor total do produto ao valor total do motorista
                $sunValue += ($product->value * $product->quantity) - $product->discount;
    
                // Adicione o produto aos produtos vendidos
                $existingProductIndex = array_search($product->product->name, array_column($produtosVendidos, 'name'));
                if ($existingProductIndex !== false) {
                    // Se o produto já estiver na lista, atualize a quantidade
                    $produtosVendidos[$existingProductIndex]['quantidade'] += $product->quantity;
                } else {
                    // Caso contrário, adicione um novo item
                    $produtosVendidos[] = [
                        'id' => $product->id,
                        'name' => $product->product->name,
                        'quantidade' => $product->quantity,
                        'precoMedio' => sprintf("%.2f", $valorMedioProduto),
                    ];
                }
            }
        }
    
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'motorista' => [
                'id' => $motorista->id,
                'name' => $motorista->name,
                'image' => $motorista->image_url,
                'sales' => $vendas,
                'values' => $values,
                'sunValue' => number_format($sunValue, 2, ',', '.'),
                'quantity' => $quantity,
                'produtos' => $produtosVendidos,
            ],
        ];
    
        return response()->json($data);
    }
    
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Adicione a validação da imagem
        ]);

        try {
            // Verifica se um arquivo de imagem foi enviado
            if ($request->hasFile('image')) {
                // Obtém o arquivo de imagem enviado
                $image = $request->file('image');

                // Define um nome único para a imagem
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Armazena a imagem no armazenamento
                $path = $image->storeAs('public/deliveryman_images', $imageName);

                // Adiciona o nome da imagem aos dados validados
                $validatedData['image'] = $imageName;
            }

            // Cria um novo entregador com os dados validados
            $deliveryman = Deliverymen::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Entregador adicionado com sucesso'
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retornar uma resposta com status de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar entregador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function lista()
    {
        // Retorna os entregadores que não foram marcados como excluídos (deleted = 0)
        $deliverymen = Deliverymen::where('deleted', 0)->get();

        // Mapeia cada entregador na coleção e adiciona a URL completa da imagem
        $deliverymen->map(function ($deliveryman) {
            $deliveryman->image_url = $deliveryman->image_url;
            return $deliveryman;
        });

        return response()->json($deliverymen);
    }

    public function getDataMotoristaForCharts(Request $request)
    {
        // Obter a data atual e o ano atual
        $dataAtual = now();
        $anoAtual = $dataAtual->year;

        // Inicializar um array vazio para armazenar as datas
        $datas = [];
        $datasBusca = [];

        // Gerar as datas do dia 1 do ano até a data atual
        for ($mes = 1; $mes <= $dataAtual->month; $mes++) {
            // Construir a data no formato desejado
            $data = Carbon::create($anoAtual, $mes, 1);
            // Adicionar ao array de datas formatando o nome do mês em português
            $datas[] = $data->isoFormat('MMMM'); // Retorna o nome do mês em português
            // Adicionar ao array de datas no formato necessário para a busca de vendas
            $datasBusca[] = $data->toDateString(); // Retorna a data no formato "ano-mês-dia"
        }

        // Obter os motoristas do banco de dados
        $motoristas = Deliverymen::where('deleted', 0)->get();

        // Inicializar um array para armazenar os detalhes dos motoristas
        $dadosMotoristas = [];

        // Percorrer os motoristas e adicionar suas informações ao array de dados
        foreach ($motoristas as $motorista) {
            // Verificar se o motorista possui uma imagem associada
            $imageUrl = $motorista->image_url ? $motorista->image_url : null;

            // Inicializar um array para armazenar as informações dos produtos vendidos por este motorista
            $produtosVendidos = [];

            // Gerar dados reais de vendas para o motorista
            $vendas = [];
            $values = [];
            $sunVlue = 0;
            $quantity = 0;
            foreach ($datasBusca as $mes) {
                // Consultar o banco de dados para obter as vendas reais do motorista para o mês atual
                $vendasDoMes = Form::where('deliveryman_id', $motorista->id)
                    ->whereYear('date', $anoAtual)
                    ->whereMonth('date', Carbon::parse($mes)->month)
                    ->sum('disk') + Form::where('deliveryman_id', $motorista->id)
                    ->whereYear('date', $anoAtual)
                    ->whereMonth('date', Carbon::parse($mes)->month)
                    ->sum('auto');

                // Adicionar o total de vendas do mês ao array de vendas
                $vendas[] = $vendasDoMes;
                $quantity += $vendasDoMes;

                // Consultar os formulários associados a este motorista
                $forms = Form::where('deliveryman_id', $motorista->id)
                    ->whereYear('date', $anoAtual)
                    ->whereMonth('date', Carbon::parse($mes)->month)
                    ->with('formProducts')
                    ->get();

                // Percorrer os formulários e extrair as informações dos produtos vendidos
                foreach ($forms as $key => $form) {

                    foreach ($form->formProducts as $key2 => $product) {

                        // Calcular o valor médio do produto considerando o desconto
                        $valorMedioProduto = (($product->value * $product->quantity) - $product->discount) / $product->quantity;

                        $sunVlue  +=  ($product->value * $product->quantity) - $product->discount;

                        // Verificar se o produto já foi registrado neste array
                        $produtoIndex = array_search($product->product->name, array_column($produtosVendidos, 'name'));

                        if ($produtoIndex === false) {
                            // Se o produto ainda não foi registrado, adicioná-lo ao array
                            $produtosVendidos[] = [
                                'id' => $product->id,
                                'name' => $product->product->name,
                                'quantidade' => $product->quantity,
                                'precoMedio' =>  sprintf("%.2f", $valorMedioProduto), // Preço médio do produto considerando o desconto
                            ];
                        } else {

                            // Se o produto já foi registrado, atualizar o preço médio
                            $produtosVendidos[$produtoIndex]['precoMedio'] = ($produtosVendidos[$produtoIndex]['precoMedio'] + $valorMedioProduto) / 2;
                        }
                    }
                }

                // Somar os valores das colunas 'total' da tabela 'form' para o motorista no mês atual
                $valorTotal = Form::where('deliveryman_id', $motorista->id)
                    ->whereYear('date', $anoAtual)
                    ->whereMonth('date', Carbon::parse($mes)->month)
                    ->sum('total');

                // Adicionar o total de vendas do mês ao array de vendas
                $values[] = sprintf("%.2f", $valorTotal);
            }
            // dd($sunVlue);
            // Adicionar os detalhes do motorista ao array de dados dos motoristas
            $dadosMotoristas[] = [

                'name' => $motorista->name,
                'image' => $imageUrl,
                'sales' => $vendas,
                'values' => $values,
                'sunValue' => number_format($sunVlue, 2, ',', '.'),
                "quantity" => $quantity,
                'produtos' => $produtosVendidos,
            ];
        }

        // Retornar os dados como uma resposta JSON
        return response()->json([
            'start_date' => $dataAtual->startOfMonth()->toDateString(), // Data inicial do mês atual
            'end_date' => $dataAtual->toDateString(), // Data final do mês atual
            'months' => $datas, // Array para armazenar os nomes dos meses
            'deliverymen' => $dadosMotoristas, // Array para armazenar os detalhes dos motoristas
        ]);
    }

    public function update(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Encontrar o entregador pelo ID
            $deliveryman = Deliverymen::findOrFail($request->deliveryman_id);

            // Verificar se uma nova imagem foi fornecida
            if ($request->hasFile('image')) {
                // Excluir a imagem anterior se existir
                if ($deliveryman->image) {
                    Storage::delete('public/deliveryman_images/' . $deliveryman->image);
                }

                // Armazenar a nova imagem
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('public/deliveryman_images', $imageName);

                // Adicionar o nome da nova imagem aos dados validados
                $validatedData['image'] = $imageName;
            }

            // Atualizar os dados do entregador
            $deliveryman->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Entregador atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retornar uma resposta com status de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar entregador: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para mostrar os detalhes do entregador
    public function show($id)
    {
        try {
            // Encontrar o entregador pelo ID
            $deliveryman = Deliverymen::findOrFail($id);

            return response()->json([
                'id' => $deliveryman->id,
                'name' => $deliveryman->name,
                'image' => $deliveryman->image_url,
                // Adicione outros campos do entregador aqui, se necessário
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retornar uma resposta com status de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter detalhes do entregador: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(string $id)
    {
        try {
            // Atualizar a coluna 'deleted' para true
            DB::table('deliverymens')
                ->where('id', $id)
                ->update(['deleted' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Entregador excluído com sucesso'
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, retornar uma resposta com status de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir entregador: ' . $e->getMessage()
            ], 500);
        }
    }
}
