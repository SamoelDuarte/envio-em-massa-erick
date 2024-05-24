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

    .radio-btn {
        display: inline-block;
        margin: 5px;
    }

    .radio-btn input[type="radio"] {
        display: none;
    }

    .radio-btn label {
        display: inline-block;
        padding: 10px 20px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .radio-btn input[type="radio"]:checked+label {
        background: #265330;
        box-shadow: 0 0 0 0.2rem rgb(0 0 0 / 92%);
        animation: shake 0.5s;
    }

    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        50% {
            transform: translateX(5px);
        }

        75% {
            transform: translateX(-5px);
        }

        100% {
            transform: translateX(0);
        }
    }
</style>

@section('content')
    <section>
        <div class="page-header-content py-3">

            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Editar Campanha</h1>
                <form id="formImagem" action="{{ route('upload.imagem') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="file" class="form-control custom-file-input" id="inputImagem" name="imagem"
                            accept="image/*" onchange="document.getElementById('formImagem').submit()">

                        <label class="input-group-text" for="inputImagem">Inserir Mais Imagens</label>
                    </div>
                </form>
            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.campaign.index') }}">Relatório de Envio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar Campanha</li>
            </ol>

        </div>
        <form id="myForm" action="{{ route('admin.campaign.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titulo">Titulo da Campanha</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $campaign->titulo) }}">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="texto">Mensagem</label>
                        <textarea name="texto" class="form-control @error('texto') is-invalid @enderror" rows="3">{{ old('texto', $campaign->texto) }}</textarea>
                        @error('texto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="right-input">
                        <button type="submit" class="btn btn-success">Atualizar</button>
                        <input type="file" name="csvFile" id="csvFile" style="display: none;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-input" style="display: flex; align-items: center;">
                        <!-- Aqui estão os radio buttons com as imagens -->
                        @foreach ($imagens as $imagem)
                            <label class="radio-img">
                                <input type="radio" name="imagem_id" value="{{ $imagem->id }}" {{ old('imagem_id', $campaign->imagem_id) == $imagem->id ? 'checked' : '' }}>
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
@endsection
