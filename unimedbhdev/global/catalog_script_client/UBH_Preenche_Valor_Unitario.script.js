function onChange(control, oldValue, newValue, isLoading) {
    if (isLoading || newValue == '') {
        g_form.clearValue('quantidade');
        g_form.clearValue('c_digo');
        return;
    }

    var codigo = g_form.getValue('c_digo');
    obterValorUnitario(codigo);
}

function obterValorUnitario(codigo) {
    var ga = new GlideAjax('ItemEstoque');
    ga.addParam('sysparm_name', 'obterValorUnitario');
    ga.addParam('sysparm_cod', codigo);

    ga.getXMLAnswer(function(response) {
        try {
            if (response && response.responseXML) {
                var valorUnitario = response.responseXML.documentElement.getAttribute('answer');
                if (valorUnitario !== null) {
                    g_form.setValue('valor_unitario_item', valorUnitario);
                } else {
                    g_form.addErrorMessage('Nenhum valor encontrado para o código especificado.');
                }
            } else {
                g_form.addErrorMessage('Resposta inválida do servidor.');
            }
        } catch (ex) {
            g_form.addErrorMessage('Erro ao processar resposta do servidor: ' + ex);
        }
    }).catch(function(error) {
        g_form.addErrorMessage('Erro na requisição Ajax: ' + error);
    });
}