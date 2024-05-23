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
</style>

@section('content')
    <section>
        <div class="page-header-content py-3">

            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Envio em Massa</h1>
                <form id="formImagem" action="{{ route('upload.imagem') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="file" class="form-control custom-file-input" id="inputImagem" name="imagem"
                            accept="image/*" onchange="document.getElementById('formImagem').submit()">

                        <label class="input-group-text" for="inputImagem">Inserir Mais Imagen</label>
                    </div>
                </form>
            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ route('admin.message.index') }}">Relatório de Envio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Envio em Massa</li>
            </ol>

        </div>
        <form id="myForm" action="{{ route('admin.message.bulk') }}" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">

                    @csrf
                    <div class="form-group">
                        <label for="">Mensagem</label>
                        <textarea name="texto" class="form-control" name="" id="" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="input-wrapper">
                            <div class="left-input">
                                <label for="csvFile" id="uploadLabel" class="btn btn-primary">Escolha um arquivo</label>
                                <small id="helpId" class="form-text text-muted">Arquivo .csv com Contatos </small>
                            </div>
                            <div class="right-input">
                                <button type="submit" class="btn btn-success ">Enviar</button>
                                <input type="file" name="csvFile" id="csvFile" style="display: none;">
                            </div>

                        </div>

                        <div id="resultado"></div>


                    </div>



                </div>
                <div class="col-md-6">
                    <div class="right-input" style="display: flex; align-items: center;">
                        <!-- Aqui estão os radio buttons com as imagens -->
                        @foreach ($imagens as $imagem)
                            <label class="radio-img">
                                <input type="radio" name="imagem_id" value="{{ $imagem->id }}">
                                <img src="{{ asset($imagem->caminho) }}" alt="Imagem">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>


    </section>
@endsection

@section('scripts')
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
