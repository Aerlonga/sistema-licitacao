const GoInfraAjax = {

    fetchForm: function (formSelector, headers = {}, additionalData = {}, customFeedback = false) {
        return new Promise((resolve, reject) => {
            const form = document.querySelector(formSelector);
            const formData = FormGoInfra.serialize(formSelector);


            if (Object.keys(additionalData).length > 0)
            {
                additionalData.forEach(item => {
                    if (item.fileName != undefined) {
                        formData.append(item.name, item.value, item.fileName);
                    } else {
                        formData.append(item.name, item.value);
                    }
                });
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const defaultHeaders = {
                'Accept': 'application/json',
            };

            const mergedHeaders = Object.assign(defaultHeaders, headers);

            if (token) {
                mergedHeaders['X-CSRF-TOKEN'] = token;
            }
            const method = form.method.toUpperCase();

            const requestOptions = {
                method: method,
                headers: mergedHeaders,
                body: formData
            };

            fetch(form.action, requestOptions)
                .then(response => {
                    if (customFeedback) {
                        resolve(response);
                    } else {
                        if (response.status >= 200 && response.status < 300) {
                            return response.json().then(data => {
                                this.defaultRedirectCallback(data, formSelector);
                                resolve(data); 
                            });
                        } else if (response.status === 422) {
                            return response.json().then(data => {
                                this.defaultErrorCallback(data.errors, formSelector);
                                reject(data.errors);
                            });
                        } else {
                            return response.json().then(data => {
                                this.genericErrorCallback(data, formSelector);
                                reject(data);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    this.genericErrorCallback(error, formSelector);
                    reject(error); 
                });
        });
    },

    defaultRedirectCallback: function(data, formSelector = null) {
        if (data.redirectUrl != null) {
            window.location.href = data.redirectUrl;
        }
    },

    defaultErrorCallback: function(errors, formSelector) {
        $(formSelector).validate().showErrors(this.converterErrosLaravelValidate(errors));
    },

    genericErrorCallback: function(errors, formSelector) {
        const form = document.querySelector(formSelector);
        Object.values(errors).forEach(error => {
            const errorDiv = `<div class="alert alert-danger" role="alert">${error}</div>`;
            form.insertAdjacentHTML('beforebegin', errorDiv);
        });
    },

    //Converte os erros do formato do Laravel para o formato da jQuery Validate
    converterErrosLaravelValidate: function(errors) {
        let convertedErrors = {};
        Object.keys(errors).forEach(fieldName => {
            convertedErrors[fieldName] = errors[fieldName][0];
        });
        return convertedErrors;
    },

    // Função para realizar uma requisição DELETE
    delete: function(url, pergunta) {
        if (confirm(pergunta)) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const requestOptions = {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };

            fetch(url, requestOptions)
                .then(response => {
                    if (response.status >= 200 || response.status < 300) {
                        return response.json().then(data => {
                            this.defaultRedirectCallback(data);
                        });
                    } else {
                        console.error('Erro na requisição DELETE:', response.statusText);
                        return response.json().then(data => {
                            this.defaultRedirectCallback(data);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição DELETE:', error);
                    return response.json().then(data => {
                        this.defaultRedirectCallback(data);
                    });
                });
        }
    },
    fetchCombo: function(selector, url, indexValue = 'value', indexLabel = ['label'], opcaoEmBranco = 'Selecione uma opção..', method='GET') {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const defaultHeaders = {
            'Accept': 'application/json',
        };

        const requestOptions = {
            method: method,
            headers: Object.assign(defaultHeaders, {
                'X-CSRF-TOKEN': token,
            }),
        };

        fetch(url, requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na consulta ao servidor');
                }
                return response.json();
            })
            .then(data => {
                FormGoInfra.processarOpcoesSelect(selector, data, indexValue, indexLabel, opcaoEmBranco);
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    },

};
