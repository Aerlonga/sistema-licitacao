(function(window, document) {
    var InputMaskGoInfra = {};
    InputMaskGoInfra.mascaraMoedaReal = function(selector, allowMinus = false) {
        var elements = document.querySelectorAll(selector);
        if (elements.length > 0) {
            elements.forEach(function(element) {
                Inputmask('currency', {
                    prefix: 'R$ ',
                    groupSeparator: '.',
                    alias: 'numeric',
                    radixPoint: ',',
                    autoGroup: true,
                    digits: 2,
                    digitsOptional: false,
                    placeholder: '0',
                    rightAlign: false,
                    allowMinus: allowMinus 
                }).mask(element);
            });
        };
    }

    InputMaskGoInfra.mascaraDecimal = function(selector, allowMinus = false) {
        var elements = document.querySelectorAll(selector);
        if (elements.length > 0) {
            elements.forEach(function(element) {
                Inputmask('decimal', {
                    radixPoint: ',',
                    groupSeparator: '',
                    autoGroup: true,
                    digits: 2,
                    digitsOptional: true,
                    placeholder: '0',
                    rightAlign: false,
                    allowMinus: allowMinus 
                }).mask(element);
            });
        } else {
            console.error("Nenhum elemento encontrado com o seletor:", selector);
        }
    };
    window.InputMaskGoInfra = InputMaskGoInfra;

})(window, document);