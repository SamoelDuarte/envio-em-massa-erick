@extends('admin.layout.app')


@section('css')
   
@endsection

@section('content')
    <!-- Botão para adicionar entregador -->
    <div class="text-right mb-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDeliverymanModal">
            Novo Motorista
        </button>
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Content Column -->
        <div class="col-lg-12 mb-4">
            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-device">
                        <table class="table table-bordered" id="tabelaEntregadores">
                            <!-- Cabeçalho da tabela -->
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>IMG</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                    <!-- Outras colunas -->
                                </tr>
                            </thead>
                            <!-- Corpo da tabela preenchido via AJAX -->
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="addDeliverymanModal" tabindex="-1" aria-labelledby="addEntregadorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEntregadorModalLabel">Adicionar Entregador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulário para adicionar entregador -->
                    <form id="addEntregadorForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Container para o preview da imagem -->
                        <div class="image-preview-container text-center">
                            <label for="image" class="image-upload-label">
                                <!-- Imagem de preview -->
                                <img id="image-preview" src="{{ asset('/image.jpeg') }}" alt="Preview da imagem"
                                    class="image-preview">
                                <!-- Botão para escolher imagem -->

                            </label>
                            <!-- Input de arquivo oculto -->
                            <input type="file" id="image" name="image" class="form-control-file"
                                style="display: none;">
                        </div>

                        <!-- Campo de nome do entregador -->
                        <div class="form-group">
                            <label for="name">Nome do Entregador</label>
                            <input type="text" class="form-control title-case" id="name" name="name" required>
                        </div>
                        <!-- Outros campos do formulário, se necessário -->
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Atualização -->
    <div class="modal fade" id="updateDeliverymanModal" tabindex="-1" role="dialog"
        aria-labelledby="updateDeliverymanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDeliverymanModalLabel">Atualizar Entregador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="updateDeliverymanForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="updateDeliverymanName">Nome:</label>
                            <input type="text" class="form-control title-case" id="updateDeliverymanName" name="name">
                            <input type="hidden" name="deliveryman_id" id="deliveryman_id">
                        </div>
                        <div class="form-group">

                            <div class="image-preview-container">
                                <label for="updateDeliverymanImage" class="image-upload-label">
                                    <img id="updateImagePreview" src="#" alt="Preview da imagem"
                                        class="image-preview">
                                </label>
                            </div>
                            <input type="file" class="form-control-file" id="updateDeliverymanImage" name="image"
                                style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('/assets/admin/js/deliveryman/index.js') }}"></script>
@endsection
