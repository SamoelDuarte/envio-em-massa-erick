<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Star Rating Form | CodingNepal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #FF8931 50%, #006F4D 50%);
        }

        .container {
            position: relative;
            width: 400px;
            background: #111;
            padding: 20px 30px;
            border: 1px solid #444;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .container .post {
            display: none;
        }

        .container .text {
            font-size: 25px;
            color: #666;
            font-weight: 500;
        }

        .container .edit {
            position: absolute;
            right: 10px;
            top: 5px;
            font-size: 16px;
            color: #666;
            font-weight: 500;
            cursor: pointer;
        }

        .container .edit:hover {
            text-decoration: underline;
        }

        .container .star-widget .input {
            display: none;
        }

        .star-widget label {
            font-size: 40px;
            color: #444;
            padding: 10px;
            float: right;
            transition: all 0.2s ease;
        }

        input:not(:checked)~label:hover,
        input:not(:checked)~label:hover~label {
            color: #fd4;
        }

        input:checked~label {
            color: #fd4;
        }

        input#rate-5:checked~label {
            color: #006F4D;
            /* Cor verde escuro */
            text-shadow: 0 0 20px #952;
        }

        #rate-1:checked~form header:before {
            content: "Não Gostei do Atendimento";
            color: #FF8931;
            /* Cor laranja */
        }

        #rate-2:checked~form header:before {
            content: "Poderia Ser Melhor";
            color: #FF8931;
            /* Cor laranja */
        }

        #rate-3:checked~form header:before {
            content: "É incrível ";
            color: #FF8931;
            /* Cor laranja */
        }

        #rate-4:checked~form header:before {
            content: "Faltou Muito Pouco Para Perfeito";
            color: #FF8931;
            /* Cor laranja */
        }

        #rate-5:checked~form header:before {
            content: "Eu simplesmente amo isso ";
            color: #006F4D;
            /* Cor verde escuro */
        }

        .container form {
            display: none;
        }

        input:checked~form {
            display: block;
        }

        form header {
            width: 100%;
            font-size: 25px;
            color: #fe7;
            font-weight: 500;
            margin: 5px 0 20px 0;
            text-align: center;
            transition: all 0.2s ease;
        }

        form .textarea {
            height: 100px;
            width: 100%;
            overflow: hidden;
        }

        form .textarea textarea,
        form-control {
            height: 100%;
            width: 100%;
            outline: none;
            color: #eee;
            border: 1px solid #333;
            background: #222;
            padding: 10px;
            font-size: 17px;
            resize: none;
        }

        .textarea textarea:focus,
        form-control {
            border-color: #444;
        }

        form .btn {
            height: 45px;
            width: 100%;
            margin: 15px 0;
        }

        form .btn button {
            height: 100%;
            width: 100%;
            border: 1px solid #444;
            outline: none;
            background: #222;
            color: #999;
            font-size: 17px;
            font-weight: 500;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        form .btn button:hover {
            background: #1b1b1b;
        }
    </style>
    <style>
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-avatar {
            width: 50px;
            /* Ajuste o tamanho conforme necessário */
            height: 50px;
            /* Ajuste o tamanho conforme necessário */
            border-radius: 50%;
            /* Isso torna a imagem redonda */
            margin-right: 10px;
        }

        .user-name {
            font-size: 18px;
            color: #fff;
            /* Cor do texto */
        }
    </style>
</head>

<body>

    <div class="container">
        @error('telefone')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
        <div class="post">
            <div class="text">Obrigado por nos avaliar!</div>
            {{-- <div class="edit">EDITAR</div> --}}
        </div>
        <div class="star-widget">


            <div class="user-info">
                <img src="{{ asset('storage') . '/' . $colaborador->imagem }}" alt="Foto do Colaborador"
                    class="user-avatar">
                <p class="user-name">{{ $colaborador->nome }}</p>
            </div>
            <input type="radio" name="rate" id="rate-5" class="input">
            <label for="rate-5" class="fas fa-star"></label>
            <input type="radio" name="rate" id="rate-4" class="input">
            <label for="rate-4" class="fas fa-star"></label>
            <input type="radio" name="rate" id="rate-3" class="input">
            <label for="rate-3" class="fas fa-star"></label>
            <input type="radio" name="rate" id="rate-2" class="input">
            <label for="rate-2" class="fas fa-star"></label>
            <input type="radio" name="rate" id="rate-1" class="input">
            <label for="rate-1" class="fas fa-star"></label>
            <form action="{{ route('admin.events.avaliacao.store') }}" method="POST">

                @csrf
                <input type="hidden" name="nota" id="nota" value="">
                <header></header>
                <div class="form-froup">
                    <small for="telefone" style="color: white">Seu Telefone </small>
                    <input type="tel" id="telefone" name="telefone" class="phone-mask form-control"
                        placeholder="Digite seu telefone" required>
                </div>
                <div class="device-id-input">

                    <input type="hidden" id="ip-device" name="ip_device" placeholder="ID do dispositivo" readonly>
                </div>

                <input type="hidden" value="{{ $colaborador->id }}" name="colaborador_id">

                <div class="textarea">
                    <textarea cols="30" placeholder="Descreva sua experiência.." name="comentario"></textarea>
                </div>
                <div class="btn">
                    <button type="submit">Publicar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const btn = document.querySelector("button");
        const post = document.querySelector(".post");
        const widget = document.querySelector(".star-widget");
        const editBtn = document.querySelector(".edit");

        const radioButtons = document.querySelectorAll('input[name="rate"]');
        const notaInput = document.getElementById('nota');

        radioButtons.forEach((radio) => {
            radio.addEventListener('change', function() {
                notaInput.value = this.id.split('-')[1];
            });
        });

        // btn.onclick = () => {
        //     widget.style.display = "none";
        //     post.style.display = "block";
        //     editBtn.onclick = () => {
        //         widget.style.display = "block";
        //         post.style.display = "none";
        //     }
        //     return false;
        // }

        $(document).ready(function() {
            $('.phone-mask').inputmask({
                mask: ["(99) 9999-9999", "(99) 99999-9999"],
                keepStatic: true,
                placeholder: ""
            });



            // Preencha o campo de ID do dispositivo

        });


        fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => {
                const ip = data.ip;
                $('#ip-device').val(ip);
              
            })
            .catch(error => console.error("Erro ao obter o endereço IP:", error));
    </script>
</body>

</html>
