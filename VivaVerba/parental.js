// Seleciona os botões e as seções correspondentes
const botaoAtividades = document.querySelector('.botao.atividades');
const botaoTempoUso = document.querySelector('.botao.tempo-uso');
const botaoConquistas = document.querySelector('.botao.conquistas');
const botaoDesempenho = document.querySelector('.botao.desempenho');

const tempoUsoContainer = document.querySelector('.tempo-uso-container');
const atividadeContainer = document.querySelector('.atividade-container');

// Inicialmente, todas as seções estão ocultas
tempoUsoContainer.style.display = 'none';
atividadeContainer.style.display = 'none';

// Função para esconder todas as seções
function esconderTodasAsSecoes() {
  tempoUsoContainer.style.display = 'none';
  atividadeContainer.style.display = 'none';
  // Aqui você pode adicionar outras seções se necessário
}

// Função para mostrar a seção específica
function mostrarSecao(secao) {
  esconderTodasAsSecoes();  // Esconde todas as seções primeiro
  secao.style.display = 'block';  // Exibe a seção específica
}

// Lógica para os botões de interação
botaoAtividades.addEventListener('click', function() {
  mostrarSecao(atividadeContainer);
});

botaoTempoUso.addEventListener('click', function() {
  mostrarSecao(tempoUsoContainer);
});

// Caso queira adicionar mais interações para outros botões, como Conquistas e Desempenho, faça como abaixo:
botaoConquistas.addEventListener('click', function() {
  alert("Botão de Conquistas clicado");
});

botaoDesempenho.addEventListener('click', function() {
  alert("Botão de Desempenho clicado");
});
