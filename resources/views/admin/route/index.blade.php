@extends('admin.layout.app')

@section('css')
@endsection

@section('content')
    <div class="page-header-content py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Rotas</h1>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                data-target="#modalNovaRota">
                <i class="fas fa-plus text-white-50"></i> Nova Rota
            </button>
        </div>
        <div class="container">
            <!-- Adapte esta parte conforme sua estrutura real -->
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Colaborador</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rotas as $rota)
                        <tr>
                            <td>{{ $rota->id }}</td>
                            <td>{{ $rota->name }}</td>
                            <td>
                                @if ($rota->colaborador)
                                    {{ $rota->colaborador->nome }}
                                @else
                                    <span class="text-danger">Não atribuído</span>
                                @endif
                            </td>
                            <td>
                                <!-- Adicione aqui os botões de ação -->
                                @if ($rota->colaborador)
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                        onclick="adicionarColaborador({{ $rota->id }})">
                                        Atualizar Colaborador
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="adicionarColaborador({{ $rota->id }})">
                                        Adicionar Colaborador
                                    </button>
                                @endif

                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#modalDeletarRota" onclick="deletarRota({{ $rota->id }})">
                                    Deletar Rota
                                </button>

                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#modalEditarRota"onclick="abrirModalEditarRota({{ $rota->id }}, '{{ $rota->name }}')">
                                    Editar Rota
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>
    <!-- Modal Novo Rota -->
    <div class="modal fade" id="modalNovaRota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nova Rota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de Nova Rota -->
                    <form id="formNovaRota">
                        @csrf
                        <div class="form-group">
                            <label for="nomeRota">Nome da Rota</label>
                            <input type="text" class="form-control" id="nomeRota" name="nomeRota" required>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="salvarNovaRota()">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deletar Rota -->
    <div class="modal fade" id="modalDeletarRota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deletar Rota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja deletar esta rota?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarDeletarRota()">Deletar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Adicionar Colaborador -->
    <div class="modal fade" id="modalAdicionarColaborador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Colaborador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdicionarColaborador" onsubmit="return adicionarColaboradorRota()">
                        <div class="form-group">
                            <label for="selectColaborador">Selecione o Colaborador:</label>
                            <select class="form-control" id="selectColaborador" required>
                                <!-- Opções do select serão preenchidas via JavaScript -->
                                <option value="">Remover Colaborador</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarRota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Rota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditarRota">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nomeRota">Nome da Rota</label>
                            <input type="text" class="form-control" id="nomeRotaEdit" name="nomeRotaEdit">
                            <input type="hidden" class="form-control" id="idRotaEdit" name="idRotaEdit">
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function salvarNovaRota() {
            var nomeRota = $('#nomeRota').val();

            $.ajax({
                url: "{{ route('admin.route.store') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'nomeRota': nomeRota
                },
                success: function(response) {
                    // Feche o modal
                    $('#modalNovoRota').modal('hide');
                    // Recarregue a página
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao salvar a nova rota.');
                }
            });
        }

        var rotaIdToDelete;

        function deletarRota(rotaId) {
            rotaIdToDelete = rotaId;
            $('#modalDeletarRota').modal('show');
        }

        function confirmarDeletarRota() {
            // Chame a rota e o método de delete no controlador
            $.ajax({
                url: "{{ route('admin.route.delete') }}",
                type: 'DELETE',
                data: {
                    id: rotaIdToDelete
                },
                success: function(response) {
                    // Feche o modal
                    $('#modalDeletarRota').modal('hide');
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao deletar a rota.');
                }
            });
        }

        function carregarColaboradores() {
            // Substitua a URL pela rota real do seu backend que retorna a lista de colaboradores
            var url = "{{ route('admin.colaborador.lista') }}";

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Limpe o select antes de adicionar os novos colaboradores
                    $('#selectColaborador').empty();

                    // Adicione os colaboradores ao select
                    for (var i = 0; i < response.length; i++) {
                        $('#selectColaborador').append('<option value="' + response[i].id + '">' + response[i]
                            .nome + '</option>');
                    }
                    $('#selectColaborador').append('<option value="">Remover Colaborador</option>');
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao carregar a lista de colaboradores.');
                }
            });
        }


        var rotaIdGlobal;

        function adicionarColaborador(rotaId) {
            rotaIdGlobal = rotaId;
            carregarColaboradores();
            $('#modalAdicionarColaborador').modal('show');
        }

        function adicionarColaboradorRota() {
            var rotaId = rotaIdGlobal; // Substitua pelo valor correto obtido do seu contexto
            var colaboradorId = $('#selectColaborador').val();

            $.ajax({
                url: "{{ route('admin.route.adicionarColaborador') }}",
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'rotaId': rotaId,
                    'colaboradorId': colaboradorId
                },
                success: function(response) {
                    // Fechar o modal
                    $('#modalAdicionarColaborador').modal('hide');
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                    // alert('Erro ao adicionar colaborador à rota.');
                }
            });

            return false;
        }

        function editarRota(rotaId) {
            $.ajax({
                url: "{{ route('admin.route.edit', ':id') }}".replace(':id', rotaId),
                type: 'GET',
                success: function(response) {
                    // Adicione o conteúdo retornado ao modal de edição
                    $('#modalEditarRota').html(response);
                    // Abra o modal
                    $('#modalEditarRota').modal('show');
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao carregar o formulário de edição da rota.');
                }
            });
        }

        function abrirModalEditarRota(id, nome) {
            $('#modalEditarRota').modal('show');
            $('#formEditarRota').attr('action', "{{ route('admin.route.edit', '') }}" + '/' + id);
            $('#nomeRotaEdit').val(nome);
            $('#idRotaEdit').val(id);
        }
    </script>
@endsection
