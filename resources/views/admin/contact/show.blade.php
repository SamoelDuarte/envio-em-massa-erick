@extends('admin.layout.app')


@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section id="device">
        <!-- Page Heading -->
        <div class="page-header-content py-3">

            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Contatos</h1>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            data-toggle="modal" data-target="#addContactModal">
                            <i class="fas fa-plus text-white-50"></i> Inserir Contato
                        </button>
                    </div>

                    <div class="col-md-6">
                        <label for="csvFile" id="uploadLabel" class="btn btn-primary">Escolha um arquivo</label>
                        <small id="helpId" class="form-text text-muted">Arquivo .csv com Contatos</small>
                        <input type="file" name="csvFile" id="csvFile" style="display: none;">
                    </div>
                </div>

            </div>


            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}">Listas de Contatos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contatos Detalhes</li>
            </ol>

        </div>
        <!-- Content Row -->
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-12 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="container-fluid">
                            <table class="table table-bordered" id="table-contact-list">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Telefone</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contact->contactLists as $contactList)
                                        <tr>
                                            <td>{{ $contactList->id }}</td>
                                            <td>{{ $contactList->phone }}</td>
                                            <td>
                                                <form action="{{ route('admin.contact.destroy', $contactList->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja deletar este contato?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Deletar</button>
                                                </form>
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


    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactModalLabel">Adicionar Contato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.contact-more-one.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control" id="phone" name="phone" maxlength="11"
                                required>
                            <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#table-contact-list').DataTable();
            $(document).ready(function() {
            $('#csvFile').change(function() {
                var file = $(this)[0].files[0];
                var contactId = '{{ $contact->id }}'; // Adicione o ID do contato
                var formData = new FormData();
                formData.append('csvFile', file);
                formData.append('contact_id', contactId); // Adicione o ID do contato ao FormData
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '/mensagem/countContact',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var totalLinhas = response.totalLinhas;
                        

                        if (totalLinhas > 0) {
                            if (confirm('Deseja salvar os '+totalLinhas+' contatos da lista?')) {
                                // Se o usuário confirmar, envia o arquivo para salvar os contatos
                                $.ajax({
                                    url: '{{ route("admin.contact.storeFile") }}', // Ajuste a rota conforme necessário
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        alert('Contatos salvos com sucesso!');
                                        location.reload();
                                    },
                                    error: function(response) {
                                        alert('Erro ao salvar os contatos.');
                                    }
                                });
                            }
                        } else {
                            alert('O arquivo não contém contatos.');
                        }
                    },
                    error: function(response) {
                        alert('Erro ao processar o arquivo.');
                    }
                });
            });
        });
        });
    </script>
@endsection
