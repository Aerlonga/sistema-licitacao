function criarConta(id) {
    SweetAlertGoInfra.confirmar("Tem certeza que deseja excluir esta licitação", function (isConfirmed) {
        if (isConfirmed) {
            $.ajax({
                url: `/login/${id}`, // URL para o endpoint de atualização
                type: 'PUT', // Método PUT para atualização
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: {
                    status: 1 // Define o status como inativo
                },
                success: function (response) {
                    alert('Licitação excluído com sucesso!');
                    $('#licitacoesTable').DataTable().ajax.reload(); // Recarrega a tabela
                    $('#visualizarLicitacaoModal').modal('hide'); // Fecha o modal
                },
                error: function () {
                    alert('Erro ao excluir a licitação.');
                }
            });
        } else {
            SweetAlertGoInfra.alerta('Exclusão cancelada.');
        }
    });
}