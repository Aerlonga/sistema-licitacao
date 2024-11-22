@extends('layouts.adminlte.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/checklist.css') }}">
@endsection

@section('conteudo-pagina')
    <div class="container">
        <h2 class="mb-4">Checklist de Documentos por Modalidade</h2>
        <div class="form-group">
            <label for="modalidadeSelect">Selecione a Modalidade:</label>
            <select id="modalidadeSelect" class="form-control">
                <option value="">Escolha uma Modalidade</option>
                <option value="concorrencia">Concorrência</option>
                <option value="pregao">Pregão</option>
                <option value="leilao">Leilão</option>
                <option value="concurso">Concurso</option>
                <option value="dispensa">Dispensa</option>
                <option value="inexigibilidade">Inexigibilidade</option>
                <option value="dialogo_competitivo">Diálogo Competitivo</option>
            </select>
        </div>

        <div id="checklistContainer" class="mt-4">
            <h4>Checklist de Documentos</h4>
            <ul id="checklist" class="list-group">
                <!-- Os itens serão carregados dinamicamente -->
            </ul>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalidadeSelect = document.getElementById('modalidadeSelect');
            const checklist = document.getElementById('checklist');

            // Documentos por modalidade
            const documentosPorModalidade = {
                concorrencia: [
                    'Edital publicado',
                    'Ata de abertura',
                    'Habilitação técnica',
                    'Propostas comerciais'
                ],
                pregao: [
                    'Edital de pregão',
                    'Aviso de licitação',
                    'Proposta de menor preço',
                    'Ata do pregão eletrônico'
                ],
                leilao: [
                    'Edital de leilão',
                    'Laudo de avaliação',
                    'Ata de arrematação'
                ],
                concurso: [
                    'Regulamento do concurso',
                    'Ata de julgamento',
                    'Resultado final'
                ],
                dispensa: [
                    'Justificativa de dispensa',
                    'Nota técnica',
                    'Contrato assinado'
                ],
                inexigibilidade: [
                    'Justificativa de inexigibilidade',
                    'Nota técnica',
                    'Contrato assinado'
                ],
                dialogo_competitivo: [
                    'Convite aos participantes',
                    'Relatório de diálogo',
                    'Proposta final',
                    'Ata de conclusão'
                ]
            };

            // Evento de mudança no select
            modalidadeSelect.addEventListener('change', function () {
                const modalidade = this.value;

                // Limpa o checklist atual
                checklist.innerHTML = '';

                if (modalidade && documentosPorModalidade[modalidade]) {
                    // Carrega os documentos para a modalidade selecionada
                    documentosPorModalidade[modalidade].forEach(doc => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item';
                        listItem.innerHTML = `
                            <input type="checkbox" class="mr-2">
                            ${doc}
                        `;
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
    </script>
@endsection


{{-- @section('scripts')
    <script src="{{ asset('js/licitacoeslista.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection --}}
