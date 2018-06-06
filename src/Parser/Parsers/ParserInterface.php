<?php

namespace App\Parser\Parsers;

use App\Parser\ParsedLinks;

interface ParserInterface
{
    /**
     * Group of the parser
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     * Name of the parser
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return the parsed data
     *
     * @param string $data
     *
     * @return ParsedLinks[]
     */
    public function parseData(string $data): array;

    /**
     * Is the data valid
     *
     * @param string $data
     *
     * @return bool
     */
    public function isValidData(string $data): bool;
}
