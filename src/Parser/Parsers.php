<?php

namespace App\Parser;

use App\Parser\Parsers\ParserInterface;

class Parsers
{

    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    public function __construct(\IteratorAggregate $parsers)
    {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    }

    public function addParser(ParserInterface $parser): self
    {
        $this->parsers[] = $parser;

        return $this;
    }

    public function getChoices(): array
    {
        $groups = [];
        foreach ($this->parsers as $parser) {
            $groups[$parser->getGroup()][$parser->getName()] = \get_class($parser);
        }
        return $groups;
    }

}
