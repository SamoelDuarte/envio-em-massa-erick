@extends('admin.layout.app')

@section('css')
    <link href="{{ asset('/assets/admin/css/styles.css') }}" rel="stylesheet">
@endsection
<style>
    /* Estilo para o input de arquivo */
    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }

    .custom-file-input::before {
        content: 'Selecionar arquivo';
        display: inline-block;
        background: #007bff;
        color: #fff;
        border: 1px solid #007bff;
        border-radius: 5px;
        padding: 8px 12px;
        outline: none;
        cursor: pointer;
    }

    .custom-file-input:hover::before {
        background: #0056b3;
    }

    .custom-file-input:active::before {
        background: #0056b3;
    }

    .custom-file-input:focus::before {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .radio-img {
        display: inline-block;
        margin-right: 10px;
    }

    .radio-img input[type="radio"] {
        display: none;
    }

    .radio-img img {
        width: 123px;
        height: 123px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .radio-img input[type="radio"]:checked+img {
        border-color: #007bff;
        /* Cor de destaque quando selecionado */
        width: 150px;
        height: 150px;
        /* Torna a borda mais redonda */
        border-radius: 50%;
        /* Adiciona uma sombra */
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        /* Remove o contorno padrão */
        outline: none;
    }

    .left-input {
        display: flex;
        justify-content: space-between;
    }
</style>

@section('content')
    <section>
        <div class="page-header-content py-3">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Contatos</h1>
            </div>
            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contatos</li>
            </ol>
        </div>
        <form id="myForm" action="{{ route('admin.contact.store') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    @csrf
                    <div class="form-group">
                        <label for="">Nome da Lista De Contatos</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="input-wrapper">
                            <div class="left-input">
                                <div>
                                    <label for="csvFile" id="uploadLabel" class="btn btn-primary">Escolha um
                                        arquivo</label>
                                    <small id="helpId" class="form-text text-muted">Arquivo .csv com Contatos </small>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success ">Salvar Lista</button>
                                    <input type="file" name="csvFile" id="csvFile" style="display: none;">
                                </div>
                            </div>

                        </div>
                        <div id="resultado"></div>
                    </div>
                </div>

            </div>
        </form>
    </section>

    <section>
        <div class="container-fluid">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Total de Contatos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->contact_lists_count }}</td>
                            <td>
                                <a href="{{ route('admin.contact.show', $contact->id) }}" class="btn btn-info"> <i
                                        class="fas fa-eye"></i></a>
                                <button class="btn btn-primary"
                                    onclick="editContact('{{ $contact->id }}', '{{ $contact->name }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" onclick="deleteContact('{{ $contact->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="editContactModal" tabindex="-1" role="dialog" aria-labelledby="editContactModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editContactModalLabel">Editar Contato</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editContactForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="contactName">Nome do Contato</label>
                                <input type="text" class="form-control" id="contactName" name="name">
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script>
        function editContact(id, name) {
            $('#editContactForm').attr('action', `/contatos/updateLista/${id}`);
            $('#contactName').val(name);
            $('#editContactModal').modal('show');
        }

        $('#editContactForm').on('submit', function(event) {
            event.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'PUT',
                url: actionUrl,
                data: form.serialize(),
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    alert('Erro ao atualizar o contato.');
                }
            });
        });

        function deleteContact(id) {
            if (confirm('Tem certeza que deseja deletar este contato?')) {
                $.ajax({
                    type: 'DELETE',
                    url: `{{ route('admin.contact.deleteLista') }}`,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        alert('Erro ao deletar o contato.');
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#csvFile').change(function() {
                var file = $(this)[0].files[0];
                var formData = new FormData();
                formData.append('csvFile', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '/mensagem/countContact',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#resultado').text('Total de Contatos a ser enviado: ' + response
                            .totalLinhas);
                    }
                });
            });
        });
    </script>
@endsection
