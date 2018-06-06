<?php

namespace App\Parser\Parsers\Kingbox;

use App\Parser\ParsedLinks;
use App\Parser\Traits\GetCountryAndChannel;

class SimpleListRtmp extends AbstractKingBox
{

    use GetCountryAndChannel;

    /**
     * @var null|ParsedLinks[]
     */
    private $parsedData;

    /**
     * Name of the parser
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Simple List - RTMP';
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
            preg_match_all('/(.*) #Name:(.*)/im', $data, $matches);
            $combined = array_combine($matches[1], $matches[2]);

            $parsed = [];
            foreach ($combined as $link => $channel) {
                if ($this->isUrlValid($link)) {
                    [$country, $channel] = $this->getCountryAndChannelFromChannel($channel);
                    $parsed[] = new ParsedLinks(trim($channel), $country, trim($link));
                }
            }
            $this->parsedData = $parsed;
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
        $this->parseData($data);

        return \count($this->parsedData) > 0;
    }

}
