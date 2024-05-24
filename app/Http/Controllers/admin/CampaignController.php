<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableSlot;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ImagemEmMassa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use SplFileObject;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with(['contactList' => function ($query) {
            $query->select('contact_list.id', 'contact_list.contact_id')
                ->withPivot('send');
        }])->get()->map(function ($campaign) {
            $campaign->total_to_send = $campaign->contactList->count();
            $campaign->total_sent = $campaign->contactList->where('pivot.send', true)->count();
            $campaign->total_not_sent = $campaign->contactList->where('pivot.send', false)->count();
            return $campaign;
        });

        return view('admin.campaign.index', compact('campaigns'));
    }

    public function deleteCampanha($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('admin.campaign.index')->with('success', 'Campanha excluída com sucesso.');
    }

    public function updateStatus(Request $request)
    {
        $campaign = Campaign::find($request->id);
        if ($campaign) {
            $campaign->status = $campaign->status == 'play' ? 'pause' : 'play';
            $campaign->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function show($id)
    {
        $campaign = Campaign::with(['contactList.contact'])->findOrFail($id);
        return view('admin.campaign.show', compact('campaign'));
    }
    public function destroyContact($campaignId, $contactListId)
    {

        $campaignContact = CampaignContact::where('campaign_id', $campaignId)
            ->where('contact_list_id', $contactListId)
            ->firstOrFail();
        $campaignContact->delete();

        return redirect()->route('admin.campaign.show', $campaignId)->with('success', 'Contato excluído com sucesso.');
    }
    public function edit($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return redirect()->route('admin.campaign.index')->with('error', 'Campanha não encontrada.');
        }
        $imagens = ImagemEmMassa::all();

        return view('admin.campaign.edit', compact('imagens', 'campaign'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }



        if (!isset($request->imagem_id)) {
            return redirect()->back()->with('error', 'Seleciono uma Imagens')->withInput();
        }

        // Encontrar a campanha pelo ID
        $campaign = Campaign::find($id);
        if (!$campaign) {
            return redirect()->route('admin.campaign.index')->with('error', 'Campanha não encontrada.');
        }

        // Atualizar os campos da campanha
        $campaign->titulo = $request->titulo;
        $campaign->texto = $request->texto;
        $campaign->imagem_id = $request->imagem_id;
        $campaign->save();

        return redirect()->route('admin.campaign.index')->with('success', 'Campanha atualizada com sucesso.');
    }
}
