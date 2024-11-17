<?php

use Huffman\Code\Node;

require_once __DIR__ . "/vendor/autoload.php";

//$input = readline("Huffman text: ");

$input = "oi mundo";//file_get_contents(__DIR__ . "/lorem.txt");

/*$huffman = new \Huffman\Code\Huffman($input);
dd($huffman->compress());*/

$charactersQuantity = array_count_values(str_split($input));

function compareNodes($a, $b) {
    return $a->getFreq() - $b->getFreq();
}

$heap = [];

foreach($charactersQuantity as $char => $frequency) {
    $node = new Node($char, $frequency);
    $heap[] = $node;
}

/*$x = [1, 2, 3, 4, 5, 6];
usort($x, function ($a, $b) {
    echo "valor $a e valor $b" . PHP_EOL;
    return $a + $b;
});

dd($x);*/

usort($heap, "compareNodes");

while(count($heap) > 1) {
    $left = array_shift($heap);
    $right = array_shift($heap);

    $combinedFreq = $left->getFreq() - $right->getFreq();
    $newNode = new Node(null, $combinedFreq, $left, $right);

    $heap[] = $newNode;

    usort($heap, "compareNodes");
}

$huffmanTree = $heap[0];

$codes = [];

function generateCodes($node, $code = "") {
    global $codes;

    if($node->getChar() !== null) {
        $codes[$node->getChar()] = $code;
        return;
    }

    generateCodes($node->getLeft(), $code . "0");
    generateCodes($node->getRight(), $code . "1");
}

generateCodes($huffmanTree);

$encodedString = "";

for($i = 0; $i < strlen($input); $i++) {
    $char = $input[$i];
    $encodedString .= $codes[$char];
}

//echo "String Codificada: " . $encodedString . PHP_EOL;

$decodedString = "";
$currentNode = $huffmanTree;

for($i = 0; $i < strlen($encodedString); $i++) {
    $bit = $encodedString[$i];

    if($bit == "0") {
        $currentNode = $currentNode->getLeft();
    } else {
        $currentNode = $currentNode->getRight();
    }

    if($currentNode->getChar() !== null) {
        $decodedString .= $currentNode->getChar();
        $currentNode = $huffmanTree;
    }
}

echo "String codificada: " . $encodedString . PHP_EOL;
echo "String decodificada: " . $decodedString . PHP_EOL;

if($decodedString === $input) {
    dd("dale");
}

dd("oi mundo");