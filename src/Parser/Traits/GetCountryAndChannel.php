<?php

namespace App\Parser\Traits;

trait GetCountryAndChannel
{

    protected function getCountryAndChannelFromChannel($channel): array
    {
        $country = null;

        // Matches countries written as this: IT: Rai 1 FHD or IRL : FM 104 or AU:36 KXAN TX Austin HD US/AU
        if (preg_match('/((\w+)( )?:)( )?(.*)/', $channel, $matches) && $matches[2] !== '' && ($matches[4] !== '' || $matches[5] !== '')) {
            $country = trim($matches[2]);
            $channel = trim(trim($matches[4]) ?: $matches[5]);
        }

        // Matches countries written as this: (IT) Boing
        if (preg_match('/\(((.){2,3})\) (.*)/', $channel, $matches) && $matches[1] !== '' && $matches[3] !== '') {
            $country = trim($matches[1]);
            $channel = trim($matches[3]);
        }

        return [$country, $channel];
    }

}
