$(document).ready(function () {
    $('#addEntregadorForm').submit(function (event) {
        // Evita o comportamento padrão do formulário
        event.preventDefault();

        // Cria um objeto FormData para enviar os dados do formulário, incluindo a imagem
        var formData = new FormData(this);

        // Envia uma requisição AJAX para a rota de armazenamento
        $.ajax({
            type: 'POST',
            url: '/motorista/store',
            data: formData,
            dataType: 'json',
            // Define o contentType e processData como false para permitir o envio de FormData
            contentType: false,
            processData: false,
            success: function (response) {
                // Fecha o modal
                $('#addDeliverymanModal').modal('toggle');

                // Exibe a mensagem de sucesso usando o SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 3000 // Tempo em milissegundos
                });

                listarEntregadores();
            },
            error: function (xhr, status, error) {
                // Exibe uma mensagem de erro
                console.log('Erro ao adicionar entregador: ' + xhr.responseText);
            }
        });
    });

    $('#addDeliverymanModal').on('hidden.bs.modal', function (e) {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    $('#updateDeliverymanForm').submit(function (event) {
        // Evita o comportamento padrão do formulário
        event.preventDefault();

        // Cria um objeto FormData para enviar os dados do formulário, incluindo a imagem
        var formData = new FormData(this);

        // Envia uma requisição AJAX para a rota de atualização
        $.ajax({
            type: 'POST',
            url: '/motorista/update', // Defina a rota adequada para a atualização
            data: formData,
            dataType: 'json',
            contentType: false, // Define o contentType como false para permitir o envio de FormData
            processData: false, // Define o processData como false para evitar que o jQuery processe os dados
            success: function (response) {
                // Fecha o modal
                $('#updateDeliverymanModal').modal('hide');

                // Exibe a mensagem de sucesso usando o SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 3000 // Tempo em milissegundos
                });

                // Recarrega a tabela de entregadores
                listarEntregadores();
            },
            error: function (xhr, status, error) {
                // Exibe uma mensagem de erro
                console.error('Erro ao atualizar entregador:', error);
            }
        });
    });

    // Função para abrir o modal de atualização ao clicar no botão Editar
    $('#tabelaEntregadores').on('click', '.editarEntregador', function () {
        var entregadorId = $(this).data('id');

        // Definir o ID do entregador no campo oculto
        $('#deliveryman_id').val(entregadorId);

        // Exemplo de requisição AJAX fictícia para obter detalhes do entregador
        $.ajax({
            type: 'GET',
            url: '/motorista/' + entregadorId, // Defina a rota adequada para obter os detalhes do entregador
            dataType: 'json',
            success: function (response) {
                // Preencha os campos do formulário com os dados do entregador
                $('#updateDeliverymanName').val(response.name);

                // Exibir a imagem atual do entregador no preview
                $('#updateImagePreview').attr('src', response.image);

                // Abra o modal de atualização
                $('#updateDeliverymanModal').modal('show');
            },
            error: function (xhr, status, error) {
                // Exibe uma mensagem de erro
                console.error('Erro ao obter detalhes do entregador:', error);
            }
        });
    });

    // Atualizar o preview da imagem quando uma nova imagem é selecionada
    $('#updateDeliverymanImage').change(function () {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#updateImagePreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    });


    function listarEntregadores() {
        $.ajax({
            type: 'GET',
            url: '/motorista/lista',
            dataType: 'json',
            success: function (response) {
                // Limpar a tabela antes de adicionar os dados
                $('#tabelaEntregadores tbody').empty();

                // Iterar sobre os entregadores e adicionar à tabela
                $.each(response, function (index, entregador) {
                    $('#tabelaEntregadores tbody').append(`
                        <tr>
                            <td>${entregador.id}</td>
                            <td><img src="${entregador.image_url}" alt="Imagem do Entregador" class="rounded-circle" style="width: 50px; height: 50px;"></td>
                            <td>${entregador.name}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm editarEntregador" data-id="${entregador.id}">Editar</button>
                                <button type="button" class="btn btn-danger btn-sm excluirEntregador" data-id="${entregador.id}">Excluir</button>
                                <a href="/motorista/detalhes/${entregador.id}" class="btn btn-info btn-sm" role="button">
                                    <i class="fas fa-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    `);
                });

                // Inicializar a tabela DataTables
                $('#tabelaEntregadores').DataTable();
            },
            error: function (xhr, status, error) {
                console.error('Erro ao buscar os entregadores:', error);
            }
        });
    }

    // Chamar a função para listar os entregadores quando a página carregar
    listarEntregadores();


    // Quando o input de arquivo de imagem é alterado
    $('#image').change(function () {
        // Verifica se um arquivo foi selecionado
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                // Atualiza o src da imagem de visualização com o conteúdo do arquivo selecionado
                $('#image-preview').attr('src', e.target.result);
            }

            // Lê o conteúdo do arquivo selecionado como uma URL de dados
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('#tabelaEntregadores').on('click', '.excluirEntregador', function () {
        var entregadorId = $(this).data('id');

        // Confirmar se o usuário deseja realmente excluir o entregador
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você não poderá desfazer esta ação!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar uma solicitação AJAX para excluir o entregador
                $.ajax({
                    type: 'POST',
                    url: '/motorista/' + entregadorId + '/delete', // Defina a rota adequada para exclusão
                    dataType: 'json',
                    success: function (response) {
                        // Exibir uma mensagem de sucesso
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000 // Tempo em milissegundos
                        });

                        // Recarregar a tabela de entregadores
                        listarEntregadores();
                    },
                    error: function (xhr, status, error) {
                        // Exibir uma mensagem de erro
                        console.error('Erro ao excluir entregador:', error);
                    }
                });
            }
        });
    });
});