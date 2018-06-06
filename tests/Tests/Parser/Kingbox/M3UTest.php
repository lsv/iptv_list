<?php

namespace Tests\Parser\Kingbox;

use App\Parser\Parsers\Kingbox\M3U;
use PHPUnit\Framework\TestCase;

class M3UTest extends TestCase
{

    static private $data = <<<'TXT'
#EXTM3U
#EXTINF:-1,======= Italia =======
http://url/live/5058.ts
#EXTINF:-1,IT: Rai 1 FHD
http://url/live/28831.ts
#EXTINF:-1,IT: Rai 1 HD
http://url/live/2760.ts
#EXTINF:-1,IT: Rai 2 FHD
http://url/live/28832.ts
#EXTINF:-1,IT: Rai 2 HD
http://url/live/2761.ts
#EXTINF:-1,IT: Rai 3 FHD
http://url/live/28833.ts
#EXTINF:-1,IT: Rai 3 HD
http://url/live/2762.ts
#EXTINF:-1,IT: Rai 4 FHD
http://url/live/28834.ts
#EXTINF:-1,IT: Rai 4 HD
http://url/live/2763.ts
#EXTINF:-1,The Fairly OddParents S05 E06
http://url/series/48573.mkv
#EXTINF:-1,The Fairly OddParents S05 E07
http://url/series/48574.mkv
#EXTINF:-1,The Fairly OddParents S05 E08
http://url/series/48575.mkv
#EXTINF:-1,The Fairly OddParents S05 E09
http://url/series/48576.mkv
#EXTINF:-1,IRL : FM 104
http://url/live/69548.ts
#EXTINF:-1,AU:36 KXAN TX Austin HD US/AU
http://url/live/50366.ts
TXT;

    protected function getParser()
    {
        return new M3U();
    }

    public function test_name(): void
    {
        $this->assertSame('M3U', $this->getParser()->getName());
    }

    public function test_group(): void
    {
        $this->assertSame('Kingbox', $this->getParser()->getGroup());
    }

    public function test_parsedData(): void
    {
        $out = $this->getParser()->parseData(self::$data);
        $this->assertCount(11, $out);

        $channel = $out[0];
        $this->assertNull($channel->getCountry());
        $this->assertSame('======= Italia =======', $channel->getChannel());
        $this->assertSame('http://url/live/5058.ts', $channel->getLink());

        $channel = $out[8];
        $this->assertSame('IT', $channel->getCountry());
        $this->assertSame('Rai 4 HD', $channel->getChannel());
        $this->assertSame('http://url/live/2763.ts', $channel->getLink());

        $channel = $out[9];
        $this->assertSame('IRL', $channel->getCountry());
        $this->assertSame('FM 104', $channel->getChannel());
        $this->assertSame('http://url/live/69548.ts', $channel->getLink());

        $channel = $out[10];
        $this->assertSame('AU', $channel->getCountry());
        $this->assertSame('36 KXAN TX Austin HD US/AU', $channel->getChannel());
        $this->assertSame('http://url/live/50366.ts', $channel->getLink());

    }

    public function test_is_valid_data()
    {
        $data = <<<'TXT'
#EXTM3U
#EXTINF:-1,======= Italia =======
http://url/live/5058.ts
TXT;

        $this->assertTrue($this->getParser()->isValidData($data));

    }

    public function test_is_invalid_data()
    {
        $data = <<<'TXT'
Hello World
TXT;

        $this->assertFalse($this->getParser()->isValidData($data));

    }

}
