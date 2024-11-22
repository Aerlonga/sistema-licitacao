document.addEventListener('DOMContentLoaded', function () {
    const gerarEquipeForm = document.getElementById('gerarEquipeForm');
    const gerarEquipeButton = document.getElementById('gerarEquipeBtn');

    if (gerarEquipeForm && gerarEquipeButton) {
        gerarEquipeForm.addEventListener('submit', function (event) {
            // Desativa o botão para evitar múltiplos cliques
            gerarEquipeButton.disabled = true;

            event.preventDefault(); // Previne o envio padrão do formulário

            $.ajax({
                url: gerarEquipeForm.dataset.url, // Obtém a URL do atributo data-url do formulário
                type: 'POST',
                data: new FormData(gerarEquipeForm),
                processData: false,
                contentType: false,
                success: function (response) {
                    // Usar SweetAlert em vez do alert padrão
                    Swal.fire({
                        title: 'Equipe Gerada com Sucesso!',
                        text: '',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Qualquer lógica adicional após o alerta
                        gerarEquipeForm.reset(); // Reseta o formulário, caso necessário
                    });
                },
                error: function (xhr) {
                    console.error('Erro ao gerar equipe:', xhr.responseText);
                    // Usar SweetAlert para mensagens de erro
                    Swal.fire({
                        title: 'Erro',
                        text: 'Houve um erro ao tentar gerar a equipe.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function () {
                    // Reativa o botão após a conclusão da requisição, com sucesso ou erro
                    gerarEquipeButton.disabled = false;
                }
            });
        });
    }
});
