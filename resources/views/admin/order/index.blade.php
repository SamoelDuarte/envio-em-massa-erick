@extends('admin.layout.app')


@section('css')
    <link href="{{ asset('/assets/admin/css/device.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section id="device">
        <!-- Page Heading -->
        <div class="page-header-content py-3">



            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pedidos</li>
            </ol>

        </div>
        <!-- Content Row -->
        <div class="row">
            <!-- Content Column -->
            <div class="col-lg-12 mb-4">
                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-device">
                            <table class="table table-bordered" id="table-order">

                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Ações</th>

                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 class="py-3 m-0">Pedido N°</h5>
                </div>
                <div class="modal-body">
                    <h2>Informações do Cliente</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Nome</label>
                            <input type="text" id="customer-name" class="form-control" 
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="">Telefone</label>
                            <input type="text" id="customer-phone" class="form-control" 
                                readonly>
                        </div>

                        <div class="col-md-12">
                            <label for="">Endereço</label>
                            <textarea id="customer-address" class="form-control"  style="height: 169px;" readonly></textarea>
                        </div>

                    </div>
                    <h2>Items do Pedidos</h2>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody id="table-items">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
                    <form action="" method="post" class="float-right">
                        @csrf
                        <input type="hidden" id="id_survey_deleta" name="id_survey_deleta">
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
    <script src="{{ asset('/assets/admin/js/order/index.js') }}"></script>
@endsection
