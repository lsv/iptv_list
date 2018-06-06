<?php

namespace App\Parser\Traits;

use App\Parser\ParsedLinks;

trait ParseM3U
{

    use GetCountryAndChannel;

    protected function parseM3U($data, callable $urlIsValid): array
    {
        $lines = explode("\n", $data);

        $groupedData = [];

        $lineId = 0;
        foreach ($lines as $line) {
            if (++$lineId === 1) {
                continue;
            }

            if ($lineId % 2 === 0) {
                $groupedData[$lineId]['info'] = $line;
            }

            if ($lineId % 2 !== 0) {
                $groupedData[$lineId - 1]['url'] = $line;
            }
        }

        $links = [];
        foreach ($groupedData as $channeldata) {
            if ($urlIsValid($channeldata['url'])) {
                $info = str_replace('#EXTINF:-1,', '', $channeldata['info']);
                [$country, $channel] = $this->getCountryAndChannelFromChannel($info);

                $links[] = new ParsedLinks(trim($channel), $country, trim($channeldata['url']));
            }
        }

        return $links;
    }

}
