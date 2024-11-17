<?php

namespace Huffman\Code;

class Node
{
    public function __construct(
        private ?string $char,
        private int $freq,
        private ?Node $left = null,
        private ?Node $right = null
    ) {}

    public function getChar(): ?string
    {
        return $this->char;
    }

    public function getFreq(): int
    {
        return $this->freq;
    }

    public function getLeft(): ?Node
    {
        return $this->left;
    }

    public function getRight(): ?Node
    {
        return $this->right;
    }
}