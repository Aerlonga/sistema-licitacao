document.addEventListener('DOMContentLoaded', function () {
    const modalidadeSelect = document.getElementById('modalidadeSelect');
    const checklist = document.getElementById('checklist');

    // Documentos por modalidade
    const documentosPorModalidade = {
        adesao: [
            'Anexo edital',
            'Anexo Ata de Registro de Preço',
            'Aceite da Adesão da Empresa',
            'Aceite da Adesão do Orgão',
            'Proposta Comercial',
            'Estudo Técnico Preliminar',
            'Termo de Referência',
            'Matriz de Risco',
            'Estimativa de custo',
            'Anexo Incisos',
            'Requisição de Despesas',
            'Certidões Negativas da Empresa',
            'CACTIC',
            'PROSET',
            'Autorização da Presidência',
            'GEORC - Documentação Orçamentária',
            'GELIC - Minuta do contrato',
        ],
        dispensa: [
            'DOD',
            'Portaria da Contratação',
            'ETP - Estudo Técnico Preliminar',
            'TR - Termo de Referência',
            'Orçamento Estimado',
            'Evidências do Orçamento Estimado - Incisos',
            'CACTIC',
            'GEORC',
            'GELIC',
        ],
        inexigibilidade: [
            'DOD',
            'Portaria da Contratação',
            'ETP - Estudo Técnico Preliminar',
            'TR - Termo de Referência',
            'Orçamento Estimado',
            'Evidências do Orçamento Estimado - Incisos',
            'Certificado de Exclusividade',
            'CACTIC',
            'GEORC',
            'GELIC',
        ],
        pregao: [
            'DOD',
            'Portaria da Contratação',
            'ETP - Estudo Técnico Preliminar',
            'TR - Termo de Referência',
            'Orçamento Estimado',
            'Evidências do Orçamento Estimado - Incisos',
            'CACTIC',
            'GEORC',
            'GELIC',
        ],
        pagamento: [
            'Autorização de Fornecimento',
            'Portaria',
            'Despacho Autuação de Pagamento',
            'Termo de Recebimento Definitivo',
            'Nota Fiscal',
            'Certidões Negativas',
            'Capa de Fatura',
            'Extrato de Título',
            'Checklist',
            'Autorização de Pagamento - DIRETORIA',
            'Despacho Pagamento - GEFIN',
        ],
        participe: [
            'Oficio Circular - Convite de Participação',
            'Edital',
            'Ata de Registro de Preços',
            'Publicação Diário Oficial',
            'Termo de Participação',
            'ETP - Estudo Técnico Preliminar',
            'Minuta Oficio de solicitação de anuência - Secretário Estadual da Administração',
            'Minuta Oficio de autorização de compra - Empresa',
            'Despacho Encaminhamento das Minutas',
            'Anexo Anuência da Empresa',
            'Certidões e Procurações da Empresa',
            'Requisição de Despesas',
            'Analise/Matriz de Risco',
            'Portaria do Gestor do Contrato',
            'Justificativa',
            'Despacho CACTIC',
            'Parecer Técnico',
            'Despacho Proset',
            'Parecer Jurídico',
            'Autorização Presidente',
            'GEORC',
            'GELIC/GEI'
        ],
        renovacao: [
            'Portaria',
            'Manifestação Positiva da Empresa',
            'Justificativa',
            'Estimativa de Custo',
            'Decreto Nº 9.900/2021',
            'Incisos',
            'Certidões Negativas',
            'Instrução Normativa - CACTIC',
            'Parecer CACTIC',
            'GEORC',
            'GELIC',
        ]
    };

    // Evento de mudança no select
    modalidadeSelect.addEventListener('change', function () {
        const modalidade = this.value;

        // Limpa o checklist atual
        checklist.innerHTML = '';

        if (modalidade && documentosPorModalidade[modalidade]) {
            // Exibe os documentos como lista simples
            documentosPorModalidade[modalidade].forEach(doc => {
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item';
                listItem.textContent = doc;
                checklist.appendChild(listItem);
            });
        } else {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item text-muted';
            listItem.textContent = 'Nenhuma modalidade selecionada ou documentos disponíveis.';
            checklist.appendChild(listItem);
        }
    });
});