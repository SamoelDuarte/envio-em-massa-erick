@extends('admin.layout.app')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
    <div class="container">
        <div id="bottom-section">
            <div class="row">
                <div class="col-sm-12">
                    <form class="form-inline float-sm-right mt-3 mt-sm-0">
                        <div class="form-group mb-sm-0">
                            <h4 for="filter-days">Filtro: &nbsp;&nbsp;</h4>
                            <div id="filter-days" class="form-control">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="page-header-content py-3">

            <div class="d-sm-flex align-items-center justify-content-between">
                <h1 class="h3 mb-0 text-gray-800">Detalhes do Motorista - {{ $motorista['name'] }}</h1>

            </div>

            <ol class="breadcrumb mb-0 mt-4">
                <li class="breadcrumb-item"><a href="/motorista">Lista Motorista</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detalhes Motorista</li>
            </ol>

        </div>
        <div class="row">
            <h1></h1>
            <div class="col-md-4">
                <!-- Aqui serão exibidos os detalhes do motorista -->
                <div id="motoristaImage">
                    <img src="{{ $motorista['image'] }}" alt="{{ $motorista['name'] }}" class="img-fluid" width="200">
                </div>
            </div>
            <div class="col-md-8">
                <h3>Nome: {{ $motorista['name'] }}</h3>
                <p id="quantidade-vendas">Total de Vendas: {{ $motorista['quantity'] }}</p>
                <p id="total-vendas">Valor Total: R$ {{ $motorista['sunValue'] }}</p>
                <div id="produtosVendidos">
                    <h4>Produtos Vendidos:</h4>
                    <ul>
                        @foreach ($motorista['produtos'] as $produto)
                            <li>{{ $produto['name'] }} - Quantidade: {{ $produto['quantidade'] }} - Preço Médio: R$
                                {{ $produto['precoMedio'] }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const Page = {
            init: () => {
                Page.setListeners();
            },

            setListeners: () => {
                // date picker

                moment.locale('pt-br');

                // Obtenha a data atual
                var currentDate = moment();

                // Defina a data de início para o primeiro dia do mês atual
                var start = moment().startOf('month');

                // Defina a data de término como a data atual
                var end = currentDate;

                function cb(start, end) {
                    $("#filter-days span").html(
                        start.format("DD/MM/YYYY") +
                        " - " +
                        end.format("DD/MM/YYYY")

                    );

                    orderDash(start.format("YYYY-MM-DD"), end.format("YYYY-MM-DD"));
                }

                function updateDriverInfo(data) {
                    // Atualize o nome do motorista
                    $('#bottom-section h1').text('Detalhes do Motorista - ' + data.motorista.name);
                    // Atualize a imagem do motorista
                    $('#motoristaImage img').attr('src', data.motorista.image);
                    // Atualize o total de vendas
                    $('#quantidade-vendas').text('Total de Vendas: ' + data.motorista.quantity);
                    // Atualize o valor total
                    $('#total-vendas').text('Valor Total: R$ ' + data.motorista.sunValue);

                    // Atualize a lista de produtos vendidos
                    var produtosHtml = '';
                    $.each(data.motorista.produtos, function(index, produto) {
                        produtosHtml += '<li>' + produto.name + ' - Quantidade: ' + produto.quantidade +
                            ' - Preço Médio: R$ ' + produto.precoMedio + '</li>';
                    });
                    $('#produtosVendidos ul').html(produtosHtml);
                }

                $("#filter-days").daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        "Hoje": [moment(), moment()],
                        "Ontem": [
                            moment().subtract(1, "days"),
                            moment().subtract(1, "days"),
                        ],
                        "Últimos 7 dias": [moment().subtract(6, "days"), moment()],
                        "Últimos 30 dias": [moment().subtract(29, "days"), moment()],
                        "Esse mês": [
                            moment().startOf("month"),
                            moment().endOf("month"),
                        ],
                        "Mês passado": [
                            moment().subtract(1, "month").startOf("month"),
                            moment().subtract(1, "month").endOf("month"),
                        ],
                    },
                    locale: {
                        format: "DD/MM/YYYY",
                        separator: " - ",
                        applyLabel: "Aplicar",
                        cancelLabel: "Cancelar",
                        fromLabel: "De",
                        toLabel: "Até",
                        customRangeLabel: "Personalizado",
                        months: [
                            "Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
                            "Jul", "Ago", "Set", "Out", "Nov", "Dez"
                        ],
                        monthsShort: [
                            "Jan", "Fev", "Mar", "Abr", "Mai", "Jun",
                            "Jul", "Ago", "Set", "Out", "Nov", "Dez"
                        ],
                        daysOfWeek: [
                            "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb",
                        ],
                        monthNames: [
                            "Janeiro",
                            "Fevereiro",
                            "Março",
                            "Abril",
                            "Maio",
                            "Junho",
                            "Julho",
                            "Agosto",
                            "Setembro",
                            "Outubro",
                            "Novembro",
                            "Dezembro",
                        ],
                        firstDay: 0,
                    },
                }, cb);

                cb(start, end);

                function orderDash(start, end) {
                    getInfo(start, end)
                }

                function getInfo(start, end) {
                    $.ajax({
                        type: "GET",
                        dataType: "JSON",
                        data: {
                            start_date: start,
                            end_date: end
                        },
                        url: "/motorista/detalhesAjax/{{ $motorista['id'] }}", // Atualize a URL para incluir o ID do motorista
                        beforeSend: () => {
                            Utils.isLoading();
                        },
                        success: (data) => {
                            updateDriverInfo(data);
                        },
                        error: (xhr) => {
                            // Trate erros
                        },
                        complete: () => {
                            Utils.isLoading(false);
                        },
                    });
                }

            },
        };
        Page.init();
    </script>
@endsection
