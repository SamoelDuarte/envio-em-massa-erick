var url = window.location.origin;
$('#table-device').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/dispositivo/getDevices",
        "type": "GET"
    },
    "columns": [{
        "data": "picture"
    }, {
        "data": "name"
    },
    {
        "data": "display_status"
    },
    {
        "data": "message_count_last_hour"
    },

    {
        "data": "status"
    }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {
        let btn = 'success';
        if (data['display_status'] == "Desconectado") {
            btn = "danger";
        }
        $('td:eq(0)', row).html('<div class="imagem-round"><img src="' + data['picture'] + '" /></div>');
        $('td:eq(2)', row).html('<button class="btn btn-' + btn + '">' + data['display_status'] + '</button>');
        $('td:eq(4)', row).html('<a href="javascript:;" data-toggle="modal" onClick="configModalDelete(' + data["id"] + ')" data-target="#modalDelete" class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></a>');


    },
});

function configModalDelete(id) {
    $('#id_device').val(id);
}