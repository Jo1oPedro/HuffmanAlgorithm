<?php

namespace Huffman\Code;

class Huffman
{
    private array $characterFrequency = [];
    private $heap = [];
    private ?Node $tree = null;
    private $codes = [];
    private $encodedString = "";

    public function __construct(
        private string $string
    ){
        $this->setCharacterFrequency();
    }

    private function setCharacterFrequency(): void
    {
        $this->characterFrequency = array_count_values(
            str_split($this->string)
        );
    }

    public function compress(): string
    {
        $this->encodedString = "";
        $this->buildHeap();
        $this->buildTree();
        $this->generateCodes($this->tree);
        $this->encodeString();
        return $this->encodedString;
    }

    private function buildHeap()
    {
        foreach($this->characterFrequency as $char => $frequency) {
            $node = new Node($char, $frequency);
            $this->heap[] = $node;
        }
        usort($this->heap, [$this, "compareNodes"]);
    }

    private function buildTree(): void
    {
        while(count($this->heap) > 1) {
            $left = array_shift($this->heap);
            $right = array_shift($this->heap);

            $combinedFrequency = $left->getFreq() - $right->getFreq();
            $newNode = new Node(null, $combinedFrequency, $left, $right);

            $this->heap[] = $newNode;

            usort($this->heap, [$this, "compareNodes"]);
        }

        $this->tree = $this->heap[0];
    }

    private function generateCodes(Node $node, string $code = ""): void
    {
        if($node->getChar() !== null) {
            $this->codes[$node->getChar()] = $code;
            return;
        }

        $this->generateCodes($node->getLeft(), $code . "0");
        $this->generateCodes($node->getRight(), $code . "1");
    }

    private function encodeString(): void
    {
        for($i = 0; $i < strlen($this->string); $i++) {
            $char = $this->string[$i];
            $this->encodedString .= $this->codes[$char];
        }
    }

    private function compareNodes(Node $nodeLeft, Node $nodeRight): int
    {
        return $nodeLeft->getFreq() - $nodeRight->getFreq();
    }

    public function decompressString()
    {
        $decompressedString = "";
        $currentNode = $this->tree;

        for($i = 0; $i < strlen($this->encodedString); $i++) {
            $bit = $this->encodedString[$i];

            if($bit == "0") {
                $currentNode = $currentNode->getLeft();
            } else {
                $currentNode = $currentNode->getRight();
            }

            if($currentNode->getChar() !== null) {
                $decompressedString .= $currentNode->getChar();
                $currentNode = $this->tree;
            }
        }

        return $decompressedString;
    }

    /*public function optimizeCompressedString(): string
    {
        $optimizedString = "";
        foreach($this->encodedString as $string) {

        }
    }*/
}