(function(window, document) {
    var SweetAlertGoInfra = {};
    SweetAlertGoInfra.sucesso = function(mensagem, titulo = 'Sucesso!', textoBotaoConfirmar = 'OK!') {
        this.alerta(mensagem, titulo, 'success', textoBotaoConfirmar)
    }

    SweetAlertGoInfra.erro = function(mensagem, titulo = 'Erro!', textoBotaoConfirmar = 'OK!') {
        this.alerta(mensagem, titulo, 'error', textoBotaoConfirmar)
    }

    SweetAlertGoInfra.alerta = function(mensagem, titulo, icone = 'info', textoBotaoConfirmar = 'OK') {
        Swal.fire({
            title: titulo,
            //text: mensagem,
            html: `<b>${mensagem}</b>`,
            //icon: icone,
            confirmButtonText: textoBotaoConfirmar,
            customClass: {
                popup: 'custom-swal-popup',
                confirmButton: 'custom-cancel-btn',
                cancelButton: 'custom-cancel-btn'
            }
        })
    }

    SweetAlertGoInfra.confirmar = function(
        mensagem, callback, titulo = 'Confirmação', icone = 'question', textoBotaoConfirmar = 'Sim', textoBotaoCancelar = 'Não'
    ) {
        Swal.fire({
            //title: titulo,
            //text: mensagem,
            html: `<b>${mensagem}</b>`,
            //icon: icone,
            showCancelButton: true,
            confirmButtonText: textoBotaoConfirmar,
            cancelButtonText: textoBotaoCancelar,
            customClass: {
                popup: 'custom-swal-popup',
                confirmButton: 'custom-confirm-btn',
                cancelButton: 'custom-cancel-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                callback(true);
            } else {
                callback(false);
            }
        });
    };

    window.SweetAlertGoInfra = SweetAlertGoInfra;

})(window, document);