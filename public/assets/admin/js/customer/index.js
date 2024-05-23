var url = window.location.origin;
$('#table-customer').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/clientes/getCustomers",
        "type": "GET"
    },
    "columns": [{
        "data": "name"
    },{
        "data": "phone"
    },
    {
        "data": "display_created_at"
        },
    
    {
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

        // $('td:eq(0)', row).html( '<div class="imagem-round"><img src="'+data['picture']+'" /></div>');
         $('td:eq(3)', row).html( '<a href="javascript:;" data-toggle="modal" onClick="configModalDelete(' + data["id"] + ')" data-target="#modalDelete" class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></a>');

    },
});