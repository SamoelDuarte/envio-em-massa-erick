@extends('admin.layout.app')

@section('css')
    <!-- Adicione seus estilos personalizados aqui -->
    <style>
        #previewImage {
            max-width: 100%;
            max-height: 200px;
        }

        .custom-error-message {
            background-color: #ffcccc;
            /* Cor de fundo vermelha claro */
            border: 1px solid #ff6666;
            /* Borda vermelha */
            padding: 10px;
            border-radius: 5px;
            color: #000000;
            /* Texto vermelho */
        }

        .img-list {
            width: 115px;
            height: 85px;
            border-radius: 16px;
        }

        .rating {
            background: #f3a4a4;
            width: 58%;
            border-radius: 15px;
            padding: 7px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header-content py-3">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800">Colaboradores</h1>
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                data-target="#modalNovoColaborador">
                <i class="fas fa-plus text-white-50"></i> Novo Colaborador
            </button>
        </div>

        @error('imagem')
            <div class="custom-error-message mt-2">
                {{ $message }}
            </div>
        @enderror
        @error('nome')
            <div class="custom-error-message mt-2">
                {{ $message }}
            </div>
        @enderror

        <div class="container">


            <!-- Adapte esta parte conforme sua estrutura real -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Média de Estrelas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($colaboradores as $colaborador)
                        <tr>
                            <td>
                                @if ($colaborador->imagem)
                                    <img src="{{ asset('storage/' . $colaborador->imagem) }}" alt="Imagem do Colaborador"
                                        class="img-list">
                                @else
                                    Sem imagem
                                @endif
                            </td>
                            <td>{{ $colaborador->nome }}</td>
                            <td>
                                <div class="rating">
                                    ({{ $colaborador->quantidade_avaliacoes }})
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $colaborador->media_estrelas)
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                        @else
                                            <i class="fas fa-star" style="color: gray;"></i>
                                        @endif
                                    @endfor
                                    <button type="button" class="btn btn-sm "
                                        onclick="verAvaliacoes({{ $colaborador->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>


                            </td>
                            <td>
                                <!-- Adicione aqui os links ou botões para editar e excluir -->
                                <!-- Exemplo: -->
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="carregarModalEditar({{ $colaborador->id }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <form action="{{ route('admin.colaborador.delete', $colaborador->id) }}" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tem certeza que deseja excluir?')">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>




        <!-- Modal Novo Colaborador -->
        <div class="modal fade" id="modalNovoColaborador" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Novo Colaborador</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulário de Novo Colaborador -->
                        <form action="{{ route('admin.colaborador.create') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <div>
                                    <label for="imagemInput" style="cursor: pointer;">
                                        <img id="previewImage" src="/imagem.png" alt="Preview da Imagem"
                                            style="display: block; border-radius: 50%; width: 100px; height: 100px;">
                                    </label>
                                </div>
                                <label for="imagem">Clique na Imagem</label>

                                <input type="file" class="form-control-file" id="imagemInput" name="imagem"
                                    accept="image/*" style="display: none;"
                                    onchange="document.getElementById('previewImage').src = window.URL.createObjectURL(this.files[0]); 
                         document.getElementById('previewImage').style.display = 'block';">

                            </div>
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control title-case" id="nome" name="nome">
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Colaborador -->
        <div class="modal fade" id="modalEditarColaborador" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Colaborador</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulário de Edição de Colaborador -->
                        <form id="formEditarColaborador" action="" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <div>
                                    <label for="imagemInputEditar" style="cursor: pointer;">
                                        <img id="previewImageEditar" src="/imagem.png" alt="Preview da Imagem"
                                            style="display: block; border-radius: 50%; width: 100px; height: 100px;">
                                    </label>
                                </div>
                                <label for="imagemEditar">Clique na Imagem</label>

                                <input type="file" class="form-control-file" id="imagemInputEditar" name="imagem"
                                    accept="image/*" style="display: none;"
                                    onchange="document.getElementById('previewImageEditar').src = window.URL.createObjectURL(this.files[0]); 
                     document.getElementById('previewImageEditar').style.display = 'block';">

                            </div>
                            <div class="form-group">
                                <label for="nomeEditar">Nome</label>
                                <input type="text" class="form-control title-case" id="nomeEditar" name="nome">
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ver Avaliações -->
        <div class="modal fade" id="modalVerAvaliacoes" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Avaliações do Colaborador</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Conteúdo das avaliações será carregado dinamicamente via Ajax -->
                        <div id="listaAvaliacoes"></div>
                    </div>
                </div>
            </div>
        </div>



    </div>

    <!-- ... (restante do código) -->
@endsection

@section('scripts')
    <!-- Adicione seus scripts personalizados aqui -->
    <script>
        function configurarModal(imagem, nome) {
            alert('aki');
            // Configura a imagem e o nome no modal
            document.getElementById('modalImage').src = imagem;
            document.getElementById('modalNome').innerHTML = 'Nome do Colaborador: ' + nome;
        }

        function printBadge() {
            // Adapte esta função conforme necessário para lidar com a impressão do crachá
            // Você pode usar window.print() ou integrar com uma biblioteca de impressão

            // Exemplo usando window.print():
            window.print();
        }

        function previewImage() {
            var input = document.getElementById('imagem');
            var preview = document.getElementById('previewImage');

            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
        // Função para abrir o modal de edição e carregar detalhes via Ajax
        function carregarModalEditar(colaboradorId) {
            // Limpar o formulário antes de abrir o modal
            $('#formEditarColaborador')[0].reset();

            // Configurar a URL para obter detalhes do colaborador via Ajax
            var url = "{{ route('admin.colaborador.edit', ':id') }}".replace(':id', colaboradorId);

            // Requisição Ajax para obter detalhes do colaborador
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Preencher o modal com os detalhes do colaborador
                        $('#previewImageEditar').attr('src', "{{ asset('storage/') }}/" + response.imagem);

                        $('#nomeEditar').val(response.nome);

                        // Configurar a URL do formulário de edição
                        $('#formEditarColaborador').attr('action', response.url);

                        // Abrir o modal de edição
                        $('#modalEditarColaborador').modal('show');
                    } else {
                        console.log(response.message);
                        alert('Erro ao carregar detalhes do colaborador.');
                    }
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao carregar detalhes do colaborador.');
                }
            });
        }

        function verAvaliacoes(colaboradorId) {
            // Limpar o conteúdo anterior do modal
            $('#listaAvaliacoes').empty();

            // Configurar a URL para obter a lista de avaliações via Ajax
            var url = "{{ route('admin.colaborador.avaliacoes', ':id') }}".replace(':id', colaboradorId);

            // Requisição Ajax para obter a lista de avaliações
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    // Preencher o modal com a lista de avaliações
                    $('#listaAvaliacoes').html(response);

                    // Abrir o modal de ver avaliações
                    $('#modalVerAvaliacoes').modal('show');
                },
                error: function(error) {
                    console.log(error);
                    alert('Erro ao carregar a lista de avaliações.');
                }
            });
        }
    </script>
@endsection
