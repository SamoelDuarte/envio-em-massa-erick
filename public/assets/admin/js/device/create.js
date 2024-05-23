

var session = $("#session_device").val();
var id_device = $("#id_device").val();

const qrcodeImg = document.getElementById("qrcode-img");
const footerQrCode = document.getElementById("footer-qr-code");


// var newSession = {
//     "url": "http://localhost:3333/sessions/add",
//     "method": "POST",
//     "timeout": 0,
//     "headers": {
//         "secret": "$2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC",
//         "Content-Type": "application/json"
//     },
//     "data": JSON.stringify({
//         "sessionId": session
//     }),
// };

// $.ajax(newSession).done(function (response) {

//     qrcodeImg.src = response['qr'];
// });


// Simulando carregamento assíncrono do QR Code em base64 (exemplo)
setTimeout(function () {

    qrcodeImg.style.display = "block";

    const preload = document.getElementById("preload");
    preload.style.display = "none";
}, 2000); // Simula um carregamento de 3 segundos. Substitua pelo seu carregamento real.


var count = 0;

function verificarCondicao() {
    var parametro = "valor_do_parametro"; // Substitua "valor_do_parametro" pelo valor real do parâmetro que você deseja passar

    $.ajax({
        url: "/dispositivo/getStatus",
        type: "GET",
        data: { sessionId: session }, // Aqui passamos o parâmetro na requisição GET
        success: function (response) {
            // Ação a ser executada em caso de sucesso
            let resposeJson = JSON.parse(response); // Aqui você pode acessar a resposta do servidor
            if (resposeJson['status'] == 'AUTHENTICATED') {

                qrcodeImg.style.display = "none";
                footerQrCode.style.display = "block";
    
                count = 6;
    
                clearInterval(intervalId); // Limpar o intervalo para parar a verificação
    
    
    
                var updateStatus = {
                    "url": "updateStatus",
                    "method": "POST",
                    "timeout": 0,
                    "data": JSON.stringify({
                        "status": resposeJson['status'],
                        "name": resposeJson['me']['name'],
                        "jid": resposeJson['me']['jid'],
                        "picture": resposeJson['me']['picture'],
                        "id": id_device,
    
                    }),
                    "headers": {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                };
    
                $.ajax(updateStatus).done(function (response) {
    
    
    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
    
                    Toast.fire({
                        icon: 'success',
                        title: "Conectado com sucesso.",
                    })
    
                });
    
    
            }
            if (count == 3) {
                location.reload();
    
            }
            console.log(count);
            count++;
        },
        error: function (xhr, status, error) {
            // Ação a ser executada em caso de erro
            console.error("Erro na requisição:", error);
        }
    });
}

const intervalId = setInterval(verificarCondicao, 3000);

// Função de verificação que será executada a cada 5 segundos
// function verificarCondicaos() {


//     var settings = {
//         "url": "http://localhost:3333/sessions/" + session + "/status",
//         "method": "GET",
//         "timeout": 0,
//         "headers": {
//             "secret": "$2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC"
//         },
//     };

//     $.ajax(settings).done(function (response) {
        
//     });
// }

// Definir o intervalo de verificação a cada 5 segundos se foi authicado


// function updateName() {
//     var name_device = $("#name_device").val();
//     var updateName = {
//         "url": "updateName",
//         "method": "POST",
//         "timeout": 0,
//         "data": JSON.stringify({
//             "name": name_device,
//             "id": id_device,

//         }),
//         "headers": {
//             "Content-Type": "application/json",
//             "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         }
//     };

//     $.ajax(updateName).done(function (response) {

//         const Toast = Swal.mixin({
//             toast: true,
//             position: 'top-end',
//             showConfirmButton: false,
//             timer: 5000,
//             timerProgressBar: true,
//             didOpen: (toast) => {
//                 toast.addEventListener('mouseenter', Swal.stopTimer)
//                 toast.addEventListener('mouseleave', Swal.resumeTimer)
//             }
//         })

//         Toast.fire({
//             icon: 'success',
//             title: "Nome Atualizado  com sucesso.",
//         })

//     });



// }