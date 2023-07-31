<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action="index.php" method="get">
Chave:
<input type="int" name="chave_list">
Valor:
<input type="int" name="valor_list">
<input type="submit" name="submitted">
</form>

<?php
// form txt save
// sepa comentar o código depois (preguica)
class ITEM {
    public $chave;
    public $valor;

    public function __construct($chave, $valor) {
        $this->chave = $chave;
        $this->valor = $valor;
    }
}

class NO {
    public $item;
    public $prox;

    public function __construct($item, $prox) {
        $this->item = $item;
        $this->prox = $prox;
    }
}

class LISTA {
    public $cabeca;
    public $tamanho;

    public function __construct() {
        $this->cabeca = new NO(new ITEM(null, null), null);
        $this->cabeca->prox = $this->cabeca;
        $this->tamanho = 0;
    }
}

function criarNo($item, $prox) {
    return new NO($item, $prox);
}

function tamanho($l) {
    return $l->tamanho;
}

function vazia($l) {
    return tamanho($l) == 0;
}

function exibirLista($l) {
    $p = $l->cabeca->prox;
    while ($p != $l->cabeca) {
    
        echo "(" . $p->item->chave . "," . $p->item->valor . ")";
        $p = $p->prox;
    }
}



function imprimirLista($l) {
    echo "Tamanho = " . tamanho($l) . "\n";
    exibirLista($l);
    echo "\n";
}

function destruir($l) {
    $atual = $l->cabeca->prox;
    while ($atual != $l->cabeca) {
        $prox = $atual->prox;
        unset($atual);
        $atual = $prox;
    }
    unset($l->cabeca);
    $l->cabeca = null;
}

function inserirNaOrdem($item, $l) {
    $pAnterior = $l->cabeca;
    $pAtual = $pAnterior->prox;

    while ($pAnterior->prox !== $l->cabeca) {
        $pAtual = $pAnterior->prox;

        if ($pAtual->item->chave > $item->chave) {
            $pAnterior->prox = criarNo($item, $pAnterior->prox);
            $l->tamanho++;
            return true;
        } elseif ($pAtual->item->chave === $item->chave) {
            return false;
        }

        $pAnterior = $pAtual;
    }

    $pAnterior->prox = criarNo($item, $pAnterior->prox);
    $l->tamanho++;
    return true;
}


function lerItens($l) {
        $n = 1; //talvez criar uma funcao para pegar o numero de itens desejados a serem adicionados (algo por ai)

        for ($i = 0; $i < $n; $i++) {
            $chave = $_GET['chave_list'];
            $valor = $_GET['valor_list'];
            $item = new ITEM($chave, $valor);
            inserirNaOrdem($item, $l);
        }
}
//gennin
function salvarListaEmTxt($l, $nomeArquivo) {
    $arquivo = fopen($nomeArquivo, "a");

    if (!$arquivo) {
        echo "Não foi possível abrir o arquivo.";
        return;
    }

    $p = $l->cabeca->prox;
    while ($p != $l->cabeca) {
        $linha = $p->item->chave . "," . $p->item->valor . PHP_EOL;
        fwrite($arquivo, $linha);
        $p = $p->prox;
    }

    fclose($arquivo);
}

// Exemplo de uso:
$list = new LISTA();


salvarListaEmTxt($list, "newfile.txt");

lerItens($list);
imprimirLista($list);
salvarListaEmTxt($list, "newfile.txt");

?>


</body>
</html>