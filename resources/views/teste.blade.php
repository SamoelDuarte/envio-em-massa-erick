<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>QR Codes</title>
  
  <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
  
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .qr-container {
      text-align: center;
      margin: 70px;
    }
  </style>
</head>

<body>
  <div class="qr-container" id="qr-container1">
    <a href="https://www.example.com/link1" target="_blank">Aracati</a>
  </div>

  <div class="qr-container" id="qr-container2">
    <a href="https://www.example.com/link2" target="_blank">Jd Nakamura</a>
  </div>

  <div class="qr-container" id="qr-container3">
    <a href="https://www.example.com/link3" target="_blank">PQ do Lago</a>
  </div>
 

  <script>
    // Função para gerar QR code para cada div
    function generateQRCode(elementId, text) {
      var container = document.getElementById(elementId);
      var qrcode = new QRCode(container, {
        text: text,
        width: 128,
        height: 128
      });
    }

    // Chame a função para cada div
    generateQRCode("qr-container1", "https://ruangas.com.br/events/avaliacao?name_rota=Aracati");
    generateQRCode("qr-container2", "https://ruangas.com.br/events/avaliacao?name_rota=JD Nakamura");	
    generateQRCode("qr-container3", "https://ruangas.com.br/events/avaliacao?name_rota=PQ do Lago");

    // generateQRCode("qr-container1", "http://localhost:8000/events/avaliacao?name_rota=Aracati");
    // generateQRCode("qr-container2", "http://localhost:8000/events/avaliacao?name_rota=JD Nakamura");	
    // generateQRCode("qr-container3", "http://localhost:8000/events/avaliacao?name_rota=PQ do Lago");
  </script>
</body>

</html>
