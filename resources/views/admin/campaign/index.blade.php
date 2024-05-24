@extends('admin.layout.app')


@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
    <style>
        .btn-play {
            background-color: #28a745;
            color: white;
        }

        .btn-pause {
            background-color: #dc3545;
            color: white;
        }
    </style>
@endsection

@section('content')
    <section id="device">

        <div class="page-header-content py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Campanhas</h1>
            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lista de Campanhas</li>
            </ol>
        </div>

        <div class="container-fluid">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>IMG</th>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Total de Contatos</th>
                        <th>Total a Enviar</th>
                        <th>Total Enviado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaigns as $campaign)
                        <tr>
                            <td><img src="{{ asset($campaign->imagem->caminho) }}" alt="Imagem" style="width: 63px;height: 63px;"></td>
                            <td><strong>{{ $campaign->titulo }}</strong></td>
                            <td>{{ $campaign->status == 'play' ? 'Ativo' : 'Inativo' }}</td>
                            <td>{{ $campaign->total_to_send }}</td>
                            <td>{{ $campaign->total_not_sent }}</td>
                            <td>{{  $campaign->total_sent }}</td>
                            <td>
                                <button class="btn {{ $campaign->status == 'play' ? 'btn-pause' : 'btn-play' }}"
                                    onclick="toggleStatus('{{ $campaign->id }}')">
                                    <i class="fas {{ $campaign->status == 'play' ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                                <a href="{{ route('admin.campaign.edit', $campaign->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-danger" onclick="deleteCampaign('{{ $campaign->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma campanha encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
                

            </table>

            <!-- Content Row -->
            {{-- <div class="row">
            <!-- Content Column -->
            <div class="col-lg-12 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-device">
                            <table class="table table-bordered" id="table-message">

                                <thead>
                                    <tr>
                                        <th scope="col">Dispositivo</th>
                                        <th scope="col">Numero</th>
                                        <th scope="col">Mensagem</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Criado em</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>


 <!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteCampaignModal" tabindex="-1" role="dialog" aria-labelledby="deleteCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="py-3 m-0">Tem certeza que deseja excluir esta campanha?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                <form id="deleteCampaignForm" action="" method="POST" class="float-right">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_campaign_id" name="campaign_id">
                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Modal -->
    <div class="modal fade" id="modalUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="py-3 m-0">Atualizar Status</h5>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body text-center">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option id="survey_active" value="active">Ativo</option>
                                <option id="survey_inative" value="inative">Inativo</option>
                                <option id="survey_closed" value="closed">Encerrado</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <input type="hidden" id="id_survey" name="id_survey">
                        <button type="submit" class="btn btn-danger btn-sm">salvar</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
          function deleteCampaign(campaignId) {
            $('#delete_campaign_id').val(campaignId);
            $('#deleteCampaignForm').attr('action', '/campanha/deletaCampanha/' + campaignId);
            $('#deleteCampaignModal').modal('show');
        }
        function toggleStatus(campaignId) {
            $.ajax({
                url: '{{ route('admin.campaign.updateStatus') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: campaignId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to change status.');
                    }
                },
                error: function() {
                    alert('Error changing status.');
                }
            });
        }
    </script>
    <script src="{{ asset('/assets/admin/js/message/index.js') }}"></script>
@endsection
