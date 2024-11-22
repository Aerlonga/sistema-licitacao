(function(window, document) {
    var GoInfraNumeros = {};
    GoInfraNumeros.converterNumeroPtBrParaIso = function(valor) {
        valor = valor.replace(/\./g, '');
        valor = valor.replace(',', '.');
        return Number(valor);
    };

    GoInfraNumeros.converterNumeroParaPtBr = function(valor) {
        const formatter = new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        return formatter.format(valor);
    };

    GoInfraNumeros.formatarMoedaReal = function(valor) {
    const formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    return formatter.format(valor);
    };

    window.GoInfraNumeros = GoInfraNumeros;

})(window, document);