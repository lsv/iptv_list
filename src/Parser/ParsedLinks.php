<?php

namespace App\Parser;

class ParsedLinks
{

    /**
     * @var string
     */
    private $channel;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string
     */
    private $link;

    /**
     * @param string $channel
     * @param string|null $country
     * @param string $link
     */
    public function __construct(string $channel, ?string $country = null, string $link)
    {
        $this->channel = $channel;
        $this->country = $country;
        $this->link = $link;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getLink(): string
    {
        return $this->link;
    }

}
