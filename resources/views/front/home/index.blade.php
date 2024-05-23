<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Site em Breve</title>
    <!-- Adicione links para bibliotecas externas via CDN aqui, se necessário -->
    <!-- Exemplo: <link rel="stylesheet" href="https://example.com/style.css"> -->
    <!-- Exemplo: <script src="https://example.com/script.js"></script> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
        }

        .countdown {
            font-size: 24px;
            margin: 20px 0;
            color: #ff4500;
        }

        /* Adicione mais estilos conforme necessário */
    </style>
</head>
<body>

    <div class="container">
        <h1>Em Breve!</h1>
        {{-- <p>Estamos trabalhando em algo incrível. Fique ligado para mais atualizações.</p> --}}
        
        {{-- <div class="countdown" id="countdown"></div> --}}
        
        {{-- <form>
            <label for="email">Inscreva-se para receber atualizações:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Inscrever-se</button>
        </form> --}}

        <!-- Adicione mais elementos interativos, formulários, etc., conforme necessário -->
    </div>

    <script>
        // // Exemplo de um contador regressivo simples
        // const countdownElement = document.getElementById('countdown');
        // const endDate = new Date('2023-12-31T00:00:00');

        // function updateCountdown() {
        //     const currentDate = new Date();
        //     const timeDifference = endDate - currentDate;

        //     const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        //     const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        //     const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
        //     const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

        //     countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;

        //     if (timeDifference <= 0) {
        //         countdownElement.innerHTML = 'O grande dia chegou!';
        //     }
        // }

        // setInterval(updateCountdown, 1000);
        // updateCountdown(); // Chamar imediatamente para evitar atrasos visuais
    </script>

</body>
</html>
