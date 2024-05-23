<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tela de Arrasta e Solta - Fluxograma Editável</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      display: flex;
      gap: 20px;
      padding: 20px;
    }

    .card {
      width: 150px;
      min-height: 100px;
      background-color: #f0f0f0;
      border-radius: 8px;
      padding: 10px;
      display: flex;
      flex-direction: column;
      cursor: move;
      position: absolute;
    }

    .card .title {
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }

    .card .content {
      font-size: 14px;
      overflow: hidden;
      flex-grow: 1;
    }

    .card input {
      margin-bottom: 5px;
    }

    .add-card-btn {
      margin-top: 20px;
      cursor: pointer;
      color: #007bff;
    }

    .canvas {
      position: relative;
      flex: 1;
      min-height: 400px;
      border: 2px dashed #ccc;
      border-radius: 8px;
      padding: 20px;
    }

    .line {
      position: absolute;
      background-color: #007bff;
      height: 2px;
      width: 0;
      z-index: -1;
    }

    .option-container {
      display: flex;
      flex-direction: column;
    }

    .option {
      display: flex;
      gap: 5px;
      margin-bottom: 5px;
    }

    .delete-option {
      cursor: pointer;
      color: red;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="canvas" id="canvas"></div>
    <div>
      <div class="add-card-btn" id="addCardBtn"><i class="fas fa-plus-circle"></i> Adicionar novo card</div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/2.14.3/js/jsplumb.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
 <!-- ... (seu código HTML, exceto o script) ... -->

 <script>
    document.addEventListener("DOMContentLoaded", function () {
      const canvas = document.getElementById("canvas");
      const addCardBtn = document.getElementById("addCardBtn");
      let cardCount = 2;

      const createOption = (optionContainer) => {
        const option = document.createElement("div");
        option.classList.add("option");
        option.innerHTML = `
          <input type="text" placeholder="Nova opção">
          <i class="fas fa-times delete-option"></i>
        `;
        optionContainer.appendChild(option);

        // Adiciona endpoint na direita para a nova opção
        const endpoint = instance.addEndpoint(option, {
          endpoint: "Dot",
          anchor: "Right",
          isSource: true,
          isTarget: true,
          connector: "Straight",
        });

        // Adiciona evento para remover a opção e o endpoint associado
        option.querySelector(".delete-option").addEventListener("click", () => {
          instance.deleteEndpoint(endpoint);
          option.remove();
        });
      };

      const createCard = (id, title, content, posX, posY) => {
        const card = document.createElement("div");
        card.classList.add("card");
        card.dataset.cardId = id;
        card.innerHTML = `
          <div class="title">${title}</div>
          <div class="content">${content}</div>
          <div class="option-container">
            <div class="option">
              <input type="text" placeholder="Opção 1">
              <i class="fas fa-times delete-option"></i>
            </div>
            <div class="option">
              <input type="text" placeholder="Opção 2">
              <i class="fas fa-times delete-option"></i>
            </div>
          </div>
          <div class="add-option-btn"><i class="fas fa-plus-circle"></i> Adicionar opção</div>
        `;
        card.style.left = posX + "px";
        card.style.top = posY + "px";
        canvas.appendChild(card);

        // Torna o card arrastável e conectável
        instance.draggable(card, {
          handle: ".title",
        });

        // Adiciona ponto de conexão na esquerda
        instance.addEndpoint(card, {
          endpoint: "Dot",
          anchor: "Left",
          isSource: true,
          isTarget: true,
          connector: "Straight",
        });

        // Adiciona dois pontos de conexão no card (um na direita e outro na esquerda)
        const optionContainers = card.querySelectorAll(".option-container .option");
        optionContainers.forEach((optionContainer) => {
          const endpoint = instance.addEndpoint(optionContainer, {
            endpoint: "Dot",
            anchor: "Right",
            isSource: true,
            isTarget: true,
            connector: "Straight",
          });
          optionContainer._jsPlumbEndpoint = endpoint;
        });

        // Adiciona evento de clique no card para edição do título
        card.querySelector(".title").addEventListener("click", () => {
          const newTitle = prompt("Edite o título:", title);
          if (newTitle !== null) {
            card.querySelector(".title").textContent = newTitle;
          }
        });

        // Adiciona evento para deletar opção e o endpoint associado
        const optionContainer = card.querySelector(".option-container");
        optionContainer.addEventListener("click", (event) => {
          if (event.target.classList.contains("delete-option")) {
            const endpoint = event.target.parentElement._jsPlumbEndpoint;
            instance.deleteEndpoint(endpoint);
            event.target.parentElement.remove();
          }
        });

        // Adiciona evento para adicionar nova opção
        const addOptionBtn = card.querySelector(".add-option-btn");
        addOptionBtn.addEventListener("click", () => {
          createOption(optionContainer);
        });
      };

      // Configuração do jsPlumb para a conexão de linhas animadas
      const instance = jsPlumb.getInstance({
        Container: canvas,
        Connector: ["Bezier", { curviness: 50 }],
        PaintStyle: { stroke: "#007bff", strokeWidth: 2 },
        Endpoint: "Blank",
        EndpointStyle: { fill: "#007bff" },
      });

      // Evento para criar a linha de conexão ao soltar o card no canvas
      instance.bind("connection", function (info) {
        instance.repaintEverything();
      });

      // Adiciona um novo card ao clicar no botão "Adicionar novo card"
      addCardBtn.addEventListener("click", function () {
        cardCount++;
        const title = `Card ${cardCount}`;
        const content = "Conteúdo do Card";
        const posX = Math.random() * (canvas.clientWidth - 150);
        const posY = Math.random() * (canvas.clientHeight - 100);
        createCard(cardCount, title, content, posX, posY);
      });

      // Cria os cards iniciais
      const initialCards = [
        { id: 1, title: "Card 1", content: "Conteúdo do Card 1", posX: 50, posY: 50 },
        { id: 2, title: "Card 2", content: "Conteúdo do Card 2", posX: 200, posY: 100 },
      ];

      initialCards.forEach((card) => {
        createCard(card.id, card.title, card.content, card.posX, card.posY);
      });
    });
  </script>
</body>

</html>
