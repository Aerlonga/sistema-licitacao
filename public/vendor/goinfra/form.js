;(function (window, document) {
  var FormGoInfra = {}

  FormGoInfra.validate = function (formSelector) {
    var form = document.querySelector(formSelector)
    if (!form) {
      console.error('Formulário não encontrado com o seletor:', formSelector)
      return
    }

    $(form).validate({
      errorClass: 'invalid-feedback',
      errorElement: 'div',
      keyUp: true,
      errorPlacement: function (error, element) {
        error.addClass('error-message')
        element.parents('.form-group').append(error)
      },
      highlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').addClass('has-error')
        $(element).addClass('is-invalid')
        $(element).next('.select2-container').addClass('select2-invalid')
        $('.form-control').not('.select2-container').removeClass('is-invalid')
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).closest('.form-group').removeClass('has-error')
        $(element).removeClass('is-invalid')
        $(element).next('.select2-container').removeClass('select2-invalid')
      },
      success: function (element) {
        $(element).closest('.form-group').removeClass('has-error')
        $(element).removeClass('is-invalid')
        $(element).next('.select2-container').removeClass('is-invalid')
      }
    })
  }

  FormGoInfra.serialize = function (formSelector) {
    var form = document.querySelector(formSelector)
    if (!form) {
      console.error('Formulário não encontrado com o ID:', formId)
      return
    }

    const data = new FormData(form)
    const inputs = form.querySelectorAll('input, select, textarea')
    const decimalInputs = Array.from(inputs).filter(
      input => input.getAttribute('inputmode') === 'decimal'
    )

    decimalInputs.forEach(input => {
      if (input.inputmask) {
        var originalValue = GoInfraNumeros.converterNumeroPtBrParaIso(
          input.inputmask.unmaskedvalue()
        )
        if (data.has(input.name)) {
          data.delete(input.name)
        }
        data.append(input.name, originalValue)
      }
    })
    return data
  }

  FormGoInfra.resetForm = function (formSelector,  pergunta = 'Têm certeza de quer fazer isso?') {
    if (pergunta == null || confirm(pergunta)) {
        $(`${formSelector} .select2-sgm`).val(null).trigger('change')
        const form = document.querySelector(formSelector)
        form.reset()
    }
  },
  FormGoInfra.processarOpcoesSelect =  function(selector, data, indexValue = 'value', indexLabel = ['label'], opcaoEmBranco = 'Selecione uma opção..') {
    const select = document.querySelector(selector);
    select.innerHTML = '';
    select.appendChild(this.criarOpcao('', opcaoEmBranco, []));
    data.forEach(item => {
        let label = ''
        if (indexLabel.length > 0) {
          indexLabel.forEach((itemLabel) => {
            label+=item[itemLabel] + ' - ';
          });
          label = label.slice(0, -2)
        } else {
          label = item[indexLabel];
        }
        select.appendChild(this.criarOpcao(item[indexValue], label, item));
    });
  },
  FormGoInfra.criarOpcao =  function(value, label, objeto) {
    const opcao = document.createElement('option');
    opcao.value = value;
    opcao.textContent = label;
    opcao.setAttribute('data-objeto', JSON.stringify(objeto));
    return opcao
  },
  FormGoInfra.limparSelect=  function(seletor) {
    const select = document.querySelector(seletor);
    if (select) {
        select.innerHTML = '';
    }
}

  window.FormGoInfra = FormGoInfra
})(window, document)
