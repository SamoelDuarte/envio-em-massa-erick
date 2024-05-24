@extends('admin.layout.app')

@section('css')
@endsection

@section('content')
<section id="device">
    <div class="page-header-content py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Relatório de Envio</h1>
            <a href="{{ route('admin.message.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus text-white-50"></i>Novo Envio em Massa
            </a>
        </div>

        <ol class="breadcrumb mb-0 mt-4">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Relatório de Envio</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-device">
                        <table class="table table-bordered" id="table-campaign">
                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>Status</th>
                                    <th>Criado em</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($campaign->contactList as $contactList)
                                    <tr>
                                        <td>{{ $contactList->phone }}</td>
                                        <td>{{ $contactList->pivot->send ? 'Enviado' : 'Não Enviado' }}</td>
                                        <td>{{ $contactList->pivot->created_at }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" onclick="deleteContact('{{ $campaign->id }}', '{{ $contactList->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="py-3 m-0">Tem certeza que deseja excluir este contato?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                <form id="deleteContactForm" action="" method="POST" class="float-right">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_campaign_id" name="campaign_id">
                    <input type="hidden" id="delete_contact_list_id" name="contact_list_id">
                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#table-campaign').DataTable();

    function deleteContact(campaignId, contactListId) {
        $('#delete_campaign_id').val(campaignId);
        $('#delete_contact_list_id').val(contactListId);
        $('#deleteContactForm').attr('action', `/campanha/${campaignId}/contact/${contactListId}`);
        $('#deleteContactModal').modal('show');
    }
</script>
@endsection
