<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajude a Desembaralhar</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/jogo-um.css">
</head>
<style>:root {
  --tile-shadow: 0 6px 14px rgba(0,0,0,0.15);
}

body {
  margin: 0;
  height: 100vh;
  font-family: 'Inter', sans-serif;
  display: flex;
  align-items: center;
  justify-content: center;

  background-image: url('Imagens/fundo.png');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.game-card {
  width: 90vw;     
  max-width: 1200px;
  padding: 2vh 2vw;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}


.exit-button {
  position: absolute;
  top: 1.5vh;
  right: 1.5vw;
  width: 10vw;     
  height: 10vw;     
  cursor: pointer;
  z-index: 3;
}

.exit-button img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}



.card-title {
  display: flex;
  justify-content: center;
  margin-bottom: 2vh;
  margin-top: 1vh;
  position: relative;
  z-index: 2;
}

.card-title .pill {
  background: rgba(255, 255, 255, 0.85);
  padding: 1.5vh 3vw;      
  border-radius: 1vw;      
  font-weight: 800;
  color: #0980a8;
  font-size: 3vw;          
  box-shadow: 0 1vh 2vh rgba(0,0,0,0.25);
}


.visual {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
}


.figure {
  width: 25vw;       
  height: 25vw;
  border-radius: 50%;
  margin-top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.figure img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
}


.play-area {
  margin-top: -5vh;       
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2vh;
}

.tiles, .slots {
  display: flex;
  gap: 1vw;              
}

.tile, .slot {
  width: 8vw;             
  height: 8vw;
  border-radius: 1vw;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 2.5vw;       
  box-shadow: var(--tile-shadow);
}

.tile.red { background: #ff5f5f; color: #fff; }
.tile.yellow { background: #ffd84a; color: #333; }
.tile.blue { background: #4aaaff; color: #fff; }

.slot {
  background: #fff;
  border: 0.4vw dashed #ccc;
  color: #0980a8;
  font-weight: 700;
  font-size: 2.5vw;
}

.slot.correct {
  border: 0.4vw solid #6ab04c;
  background-color: #e8f5e9;
}

.slot.incorrect {
  border: 0.4vw solid #ff5f5f;
  background-color: #ffebee;
  animation: shake 0.5s;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
</style>
<body>
  <div class="game-card">
   
    <div class="exit-button">
      <img src="imagens/sair.png" alt="Sair">
    </div>

    <header class="card-title">
      <div class="pill">AJUDE A DESEMBARALHAR!</div>
    </header>

    <section class="visual">
      <div class="figure">
        <img src="imagens/sol.png" alt="Figura do jogo" class="main-figure">
      </div>

      <div class="game-card" id="game-script-container">
            <div class="tiles">
          <div class="tile red" draggable="true">L</div>
          <div class="tile yellow" draggable="true">O</div>
          <div class="tile blue" draggable="true">S</div>
        </div>
    </div>

        <div class="slots">
          <div class="slot">S</div>
          <div class="slot"></div>
          <div class="slot"></div>
        </div>
      </div>
    </section>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', () => {

    // BANCO DE DADOS DAS PALAVRAS
    const wordsData = [
        { word: 'SOL', image: 'sol.png', colors: ['#ff5f5f', '#ffd84a', '#4aaaff'] },
        { word: 'MAÇÃ', image: 'maca2.png', colors: ['#ff5f5f', '#6ab04c', '#ffd84a', '#4aaaff'] },
        { word: 'PATO', image: 'pato.png', colors: ['#ffd84a', '#4aaaff', '#ff5f5f', '#6ab04c'] },
        { word: 'ROSA', image: 'rosa.png', colors: ['#ff5f5f', '#ffd84a', 'pink', '#4aaaff'] },
        { word: 'NUVEM', image: 'nuvem2.png', colors: ['#4aaaff', '#ff5f5f', '#6ab04c', '#ffd84a', 'grey'] }
    ];

    let currentWordIndex = 0;
    let draggedTile = null;


    // SELETORES DOS ELEMENTOS HTML
    const tilesContainer = document.querySelector('.tiles');
    const slotsContainer = document.querySelector('.slots');
    const figureImage = document.querySelector('.main-figure');
    const exitButton = document.querySelector('.exit-button');
    
    
    exitButton.addEventListener('click', () => {
        alert("Voltando ao menu...");
        window.location.href = 'index.php';
    });


    // FUNÇÃO PRINCIPAL PARA INICIAR O JOGO
    function setupGame() {
        // Limpa a área de jogo para a nova palavra
        tilesContainer.innerHTML = '';
        slotsContainer.innerHTML = '';

        // Pega a palavra e imagem da rodada atual
        const currentData = wordsData[currentWordIndex];
        const word = currentData.word;
        const image = currentData.image;

        figureImage.src = `imagens/${image}`;
        figureImage.alt = word;

        // Embaralha as letras da palavra para criar o desafio
        let shuffledLetters;
        do {
            shuffledLetters = word.split('').sort(() => Math.random() - 0.5);
        } while (shuffledLetters.join('') === word); // Garante que a palavra não apareça já resolvida

        // Cria os blocos de letras (tiles) embaralhados
        shuffledLetters.forEach((letter, index) => {
            const tile = document.createElement('div');
            tile.classList.add('tile');
            tile.textContent = letter;
            tile.draggable = true;
            tile.style.backgroundColor = currentData.colors[index % currentData.colors.length]; // Aplica cores
            tile.style.color = 'white';
            tilesContainer.appendChild(tile);
        });
        
        // Cria os espaços vazios (slots) para preencher
        for (let i = 0; i < word.length; i++) {
            const slot = document.createElement('div');
            slot.classList.add('slot');
            slotsContainer.appendChild(slot);
        }

        addDragAndDropListeners();
    }


    // LÓGICA DE ARRASTAR E SOLTAR (DRAG & DROP)
    function addDragAndDropListeners() {
        const tiles = document.querySelectorAll('.tile');
        const slots = document.querySelectorAll('.slot');

        // Eventos para as LETRAS que são arrastadas
        tiles.forEach(tile => {
            tile.addEventListener('dragstart', (e) => {
                draggedTile = e.target;
                setTimeout(() => {
                    e.target.style.display = 'none'; // Esconde a letra original enquanto arrasta
                }, 0);
            });

            tile.addEventListener('dragend', () => {
                setTimeout(() => {
                    if (draggedTile) {
                        draggedTile.style.display = 'flex'; // Mostra a letra novamente se o arraste falhar
                        draggedTile = null;
                    }
                }, 0);
            });
        });

        // Eventos para os ESPAÇOS que recebem as letras
        slots.forEach(slot => {
            slot.addEventListener('dragover', (e) => {
                e.preventDefault(); // Necessário para permitir o 'drop'
            });

            slot.addEventListener('drop', (e) => {
                e.preventDefault();
                if (e.target.classList.contains('slot') && e.target.textContent === '') {
                    // Coloca a letra arrastada no espaço vazio
                    e.target.textContent = draggedTile.textContent;
                    e.target.style.backgroundColor = draggedTile.style.backgroundColor;
                    e.target.style.color = draggedTile.style.color;
                    draggedTile.remove(); // Remove a letra da área original
                    draggedTile = null;
                    checkIfComplete();
                }
            });
        });
    }

    // VERIFICAÇÃO DO RESULTADO
    function checkIfComplete() {
        const slots = document.querySelectorAll('.slot');
        let wordFromSlots = '';
        let allSlotsFilled = true;

        slots.forEach(slot => {
            if (slot.textContent === '') {
                allSlotsFilled = false;
            }
            wordFromSlots += slot.textContent;
        });

        if (allSlotsFilled) {
            const correctWord = wordsData[currentWordIndex].word;
            if (wordFromSlots === correctWord) {
                slots.forEach(slot => slot.style.border = '0.4vw solid #6ab04c');
                
                // Chama a página update_score.php para incrementar o score
                fetch('update_score.php', {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Score atualizado:', data);
                })
                .catch(error => {
                    console.error('Erro ao atualizar o score:', error);
                });

                setTimeout(() => {
                    currentWordIndex = (currentWordIndex + 1) % wordsData.length; // Vai para a próxima palavra
                    setupGame(); // Recomeça o jogo com a nova palavra
                }, 1500);

            } else {
                slots.forEach(slot => slot.style.border = '0.4vw solid #ff5f5f');
                
                setTimeout(() => {
                    setupGame(); // Reinicia o mesmo desafio
                }, 1500); // Espera 1.5 segundos e reinicia
            }
        }
    }

    // INICIA O JOGO PELA PRIMEIRA VEZ
    setupGame(); 
});
</script>
</body>
</html>