<?php

require_once("modelo/Palpite.php");

echo '<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">';
echo "<style>* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background-color: lightblue;
    color: black;
    font-family: sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: 'Montserrat', serif;
}
.container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    flex-wrap: wrap;
    justify-content: center;
}

header {
    background-color:#0099cc;
    color: white;
    padding: 15px;
    width: 100%;
    text-align: center;
    margin-bottom: 20px;
}

.imagem, .sorteado, .dica {
    margin: 10px;
    text-align: center;
}

.imagem, .sorteado {
    width: 200px;
    height: 200px;
    background-color: white;
    border: 2px solid black;
    border-radius: 50%;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
}

img {
    border-radius: 8px;
    margin: 5px;
}
img:hover {
    transition: all ease-in-out 0.8s;
    transform:scale(1.1);
}
a {
    text-decoration: none;
    color: black;
    text-align: center;
}
h1, h2, h3 {
    margin: 5px;
}
.voltar {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    text-decoration: none;
    color: black;
    padding: 10px 20px;
    background: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}
.botao {
    padding: 10px 20px;
    background: green;
    color: black;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

</style>";

$p1 = new Pokemon("Bulbasaur", "https://assets.pokemon.com/assets/cms2/img/pokedex/detail/001.png");
$p2 = new Pokemon("Charmander", "https://assets.pokemon.com/assets/cms2/img/pokedex/detail/004.png");
$p3 = new Pokemon("Butterfree", "https://assets.pokemon.com/assets/cms2/img/pokedex/detail/012.png");

$palpites = array($p1, $p2, $p3);

if (isset($_GET["sorteado"])) {
    $numeroSorteado = $_GET["sorteado"];
} else {
    $numeroSorteado = sorteio($palpites);
}

echo "<header><h1>Sorteio de Pokemons</h1></header>";

echo "<div class='container'>";
foreach ($palpites as $chave => $p) {
    $i = $chave + 1;
    echo ("<div class='imagem'><a href='index.php?palpite={$i}&sorteado={$numeroSorteado}'><img src='{$p->getLink()}' style='width:128px; height:128px; display:block'>{$p->getNome()}</a></div>");
}
echo "</div>";

if (isset($_GET["palpite"])) {
    $chute = $_GET["palpite"];

    if (isset($_GET["dica"]) && $_GET["dica"] == 1) {
        echo verDica($palpites, $numeroSorteado, $chute);
        exit();
        //para nao deixar sortear de novo
    }

    if ($chute ==  $numeroSorteado) {
        $chute = $chute - 1;
        $cor = "green";
        echo "<style> .imagem {display:none;} .sorteado {display:block;}</style>";
        echo "<h1 style='color:{$cor};'>Parabéns!!! Você acertou qual Pokémon foi sorteado!</h1>";
        echo ("<div class='sorteado'><img src='{$palpites[$chute]->getLink()}' style='width:128px; height:128px;'>{$palpites[$chute]->getNome()}</div>");
        echo "<a href='index.php' class='botao'>Jogar novamente</a>";
    } else {
        $cor = "red";
        echo "<h2 style='color:{$cor};'>Você errou! Tente novamente.</h2>";
        $url = "index.php?palpite={$chute}&dica=1&sorteado={$numeroSorteado}";
        echo "<a href='{$url}' class='voltar'>Ver dica</a>";
    }
} else {
    echo "<h2>Você precisa informar um palpite! Escolha um dos Pokémon acima.</h2>";
}

function sorteio(array $ar)
{
    return array_rand($ar) + 1;
}

function verDica($a, $b, $c)
{
    $dica = $b - 1;
    $dicaLink = $a[$dica]->getLink();
    $style = "<style>.imagem { display: none; }.dica { display: block; opacity: 0.05; }</style>";
    $d = $style;
    $d .= "<div class='dica'><img src='{$dicaLink}' style='width:128px; height:128px;'></div>";
    $d .= "<a href='index.php?palpite={$c}&sorteado={$b}' class='voltar'><img src='back.png' style='width:20px; height:20px;'> Voltar à página anterior</a>";
    return $d;
}
