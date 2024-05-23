<?php

namespace App\Http\Controllers;

use App\Models\Deliverymen;
use App\Models\Form;
use App\Models\PaymentMethod;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index()
    {
       
       
            $forms = Form::all();
       
        return view('admin.form.index',compact('forms'));
    }
    public function create()
    {
        $motoristas = Deliverymen::all();
        $products = Product::all();
        $payments = PaymentMethod::all();
        return view('admin.form.create', compact('motoristas', 'products', 'payments'));
    }

    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $validatedData = $request->validate([
            'motorista' => 'required',
            'data' => 'required|date',
            'maq' => 'required',
            'product_id' => 'required|array',
            'product_value' => 'required|array',
            'desconto' => 'required|array',
            'payment_id' => 'required|array',
            'product_id.*' => 'exists:products,id', // Garante que os IDs dos produtos existam na tabela de produtos
            'payments' => 'required|array',
        ]);


        $verifiqueForm = Form::where(['date' => $validatedData['data'] , 'deliveryman_id' => $request->motorista])->first();

        if($verifiqueForm){
            return redirect()->back()->with('error','Ja tem um formularia com essa DATA e com esse Motorista')->withInput();
            exit;
        }
         
        // Criar uma nova instância do modelo Form
        $form = new Form();
        $user_id = session('userData');
        // Convertendo a string da data em um objeto Carbon
 $data = Carbon::createFromFormat('Y-m-d', $validatedData['data']);
        // Preencher os dados do formulário no modelo

       

        $form->deliveryman_id = $validatedData['motorista'];
        $form->date =   $data;
        $form->maq = $validatedData['maq'];
        $form->total =  $request->input('total', 0);
        $form->disk = $request->disk;
        $form->auto = $request->auto;
        $form->user_id = $user_id->id;
        // Valor padrão de desconto é 0

        // Salvar o modelo no banco de dados
        $form->save();

        // Salvar os produtos selecionados na tabela de relação form_product
        $products = [];
        // Vincula os produtos ao formulário
        
        foreach ($request->product as $index => $quantidade) {
        
            
            if($quantidade){
                $product_id = $validatedData['product_id'][$index];
                $value = $validatedData['product_value'][$index];
                $desconto = Utils::prepareMoneyForDatabase($validatedData['desconto'][$index]);
                if($desconto == ""){
                    $desconto = null;
                }
               
                $products[$product_id] = ['value' => $value , "quantity" => $quantidade,"discount" => $desconto];
            }
          
           
        }
        
        $form->products()->sync($products);

        // dd($request->all());
        // Salvar os métodos de pagamento na tabela de relação form_payment
        $payments = [];
        foreach ($request->payments as $key => $value) {
            $id_payment = $validatedData['payment_id'][$key];

            if($value){
                $payments[$id_payment] = ['value' =>  Utils::prepareMoneyForDatabase($value)];
            } 
        }
        $form->payments()->sync($payments);

        // Retornar uma resposta de sucesso
     return redirect('/formulario')->with('success', 'Formulário enviado com sucesso');
    }

    
}
