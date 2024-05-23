var url = window.location.origin;
$('#table-order').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/pedidos/getOrders",
        "type": "GET"
    },
    "columns": [{
        "data": "id"
    }, {
        "data": "customer.name"
    },
    {
        "data": "sum_price_items"
    },

    {
        "data": "display_status"
    }, {
        "data": "display_created_at"
    }
        , {
        "data": "display_created_at"
    }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {


        $('td:eq(1)', row).html('<label>' + data['customer'].name + ' / ' + data['customer'].phone + '</label>');
        $('td:eq(5)', row).html('<a href="javascript:;" data-toggle="modal" onClick="configModal(' + data["id"] + ')" data-target="#modalInfo" class="btn btn-sm btn-gray delete"><i class="fa fa-eye"></i></a>');


    },
});

function configModal(id) {
    const dados = {
        id: id
    };
    // Fazendo a requisição AJAX POST
    $.ajax({
        url: "pedidos/getOrder", // Substitua pela URL do seu servidor
        type: "GET",
        data: dados,
        success: function (responseJson) {
            const response = JSON.parse(responseJson);

            $('#customer-name').val(response.customer.name);
            $('#customer-phone').val(response.customer.phone);
            $("#customer-address").text(response.customer.location);
            // Ação a ser executada em caso de sucesso
            $("#resposta").html("Requisição bem-sucedida: " + response);


            $("#table-items").empty();
            response.items.forEach(function (item) {
                console.log(item);
                // Dados do novo item a serem adicionados
                const nomeItem = item.id;
                const valorItem = item.price;

                // Criação do novo elemento <tr> com as células <td> contendo os dados do item
                const novoItem = `
                <tr>
                    <td>${nomeItem}</td>
                    <td>${valorItem}</td>
                </tr>
                `;
              
                // Adiciona o novo item à tabela, no final do <tbody> com ID "table-items"
                $("#table-items").append(novoItem);


            });



        },
        error: function (xhr, status, error) {
            // Ação a ser executada em caso de erro
            $("#resposta").html("Erro na requisição: " + error);
        }
    });
}