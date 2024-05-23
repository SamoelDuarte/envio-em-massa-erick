var url = window.location.origin;
$('#table-chat').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/atendimento/getChats",
        "type": "GET"
    },
    "columns": [{
        "data": "await_answer"
    },
    {
        "data": "await_answer"
        },
    
    {
        "data": "await_answer"
    }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {
        let disable = '';
        if(data['display_status'] == "Finalizado"){
            disable = "disabled";
        }
        $('td:eq(0)', row).html('<label>' + data['customer'].name + ' / ' + data['customer'].phone + '</label>');
        $('td:eq(1)', row).html('<label>' + data['display_status'] + '</label>');
       // $('td:eq(2)', row).html('<button class="btn btn-'+btn+'">Alterar Status</button>');
         $('td:eq(2)', row).html( '<a href="javascript:;"  data-toggle="modal" onClick="configModalUp(' + data["id"] + ')" data-target="#modalUp" class="btn btn-sm btn-warning '+disable+'"> <i class="far fa-edit"></i></a>');


    },
});

function configModalUp(id){
    $('#id_chat').val(id);
}