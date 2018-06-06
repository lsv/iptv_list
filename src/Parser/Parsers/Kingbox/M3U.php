<?php

namespace App\Parser\Parsers\Kingbox;

use App\Parser\ParsedLinks;
use App\Parser\Traits\ParseM3U;

class M3U extends AbstractKingBox
{

    use ParseM3U;

    /**
     * @var ParsedLinks[]|null
     */
    private $parsedData;

    /**
     * Name of the parser
     *
     * @return string
     */
    public function getName(): string
    {
        return 'M3U';
    }

    /**
     * Return the parsed data
     *
     * @param string $data
     *
     * @return ParsedLinks[]
     */
    public function parseData(string $data): array
    {
        if (! $this->parsedData) {
            $this->parsedData = $this->parseM3U($data, function($url) {
                return $this->isUrlValid($url);
            });
        }

        return $this->parsedData;

    }

    /**
     * Is the data valid
     *
     * @param string $data
     *
     * @return bool
     */
    public function isValidData(string $data): bool
    {
        return \count($this->parseData($data)) > 0;
    }


}
