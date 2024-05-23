<!-- Conteúdo da view avaliacoes.blade.php -->
<div>
    <h3>{{ $colaborador->nome }}</h3>

    @if($avaliacoes->isEmpty())
        <p>Nenhuma avaliação disponível.</p>
    @else
        <div class="row">
            @foreach($avaliacoes as $avaliacao)
                <div class="col-md-12 mb-4">
                    <div class="card">
                      
                        <div class="card-body">
                            <h5 class="card-title">Nome {{ $avaliacao->telefone }}</h5>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avaliacao->nota)
                                    <i class="fas fa-star" style="color: yellow;"></i>
                                    @else
                                    <i class="fas fa-star" style="color: gray;"></i>
                                    @endif
                                @endfor
                              
                            </div>
                            <p class="card-text">{{ $avaliacao->comentario }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
