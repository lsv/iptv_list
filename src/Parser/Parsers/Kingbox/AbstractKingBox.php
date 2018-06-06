<?php

namespace App\Parser\Parsers\Kingbox;

use App\Parser\Parsers\AbstractParsers;

abstract class AbstractKingBox extends AbstractParsers
{

    /**
     * Group of the parser
     *
     * @return string
     */
    public function getGroup(): string
    {
        return 'Kingbox';
    }

    protected function isUrlValid($url): bool
    {
        return strpos($url, 'live') !== false;
    }

}
