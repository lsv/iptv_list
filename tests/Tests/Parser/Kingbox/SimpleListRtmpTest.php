<?php

namespace Tests\Parser\Kingbox;

use App\Parser\Parsers\Kingbox\SimpleListRtmp;
use PHPUnit\Framework\TestCase;

class SimpleListRtmpTest extends TestCase
{

    private $data = <<<'TXT'
http://url/live/123/5058.ts #Name: ======= Italia =======
http://url/live/123/28831.ts #Name: IT: Rai 1 FHD
http://url/live/123/2760.ts #Name: IT: Rai 1 HD
http://url/live/123/5940.ts #Name: IT: Rai Storia HD
http://url/live/123/5042.ts #Name: IT: Rai Scuola HD
http://url/live/123/3686.ts #Name: ---------- IT Bambini -----------
http://url/live/123/5937.ts #Name: IT: Baby TV
http://url/movie/123/33879.mkv #Name: Darkest Hour (2018) #GR
http://url/movie/123/33900.mkv #Name: Disorder la guardia del corpo # IT
http://url/movie/123/33902.avi #Name: Doppio Gioco # IT
http://url/movie/123/33913.avi #Name: Falchi  2017 # IT
http://url/series/123/48112.mp4 #Name: Kevin Can Wait # DE S02 E19
http://url/series/123/48053.mp4 #Name: Atlanta # DE S01 E01
http://url/live/123/5949.ts #Name: IT: Film In Sala 1: Sniper Ultimate Kill
http://url/movie/123/10791.avi #Name: Total Recall #Mémoires Programmées
http://url/live/123/9182.ts #Name: (IT) Boing
http://url/live/123/69548.ts #Name: IRL : FM 104
http://url/live/123/50366.ts #Name: AU:36 KXAN TX Austin HD US/AU
TXT;

    protected function getParser()
    {
        return new SimpleListRtmp();
    }

    public function test_name(): void
    {
        $this->assertSame('Simple List - RTMP', $this->getParser()->getName());
    }

    public function test_group(): void
    {
        $this->assertSame('Kingbox', $this->getParser()->getGroup());
    }

    public function test_parseData(): void
    {
        $output = $this->getParser()->parseData($this->data);

        $this->assertCount(11, $output);

        $this->assertSame('http://url/live/123/5058.ts', $output[0]->getLink());
        $this->assertNull($output[0]->getCountry());
        $this->assertSame('======= Italia =======', $output[0]->getChannel());

        $this->assertSame('http://url/live/123/28831.ts', $output[1]->getLink());
        $this->assertSame('IT', $output[1]->getCountry());
        $this->assertSame('Rai 1 FHD', $output[1]->getChannel());

        $this->assertSame('http://url/live/123/3686.ts', $output[5]->getLink());
        $this->assertNull($output[5]->getCountry());
        $this->assertSame('---------- IT Bambini -----------', $output[5]->getChannel());

        $this->assertSame('http://url/live/123/5949.ts', $output[7]->getLink());
        $this->assertSame('IT', $output[7]->getCountry());
        $this->assertSame('Film In Sala 1: Sniper Ultimate Kill', $output[7]->getChannel());

        $this->assertSame('http://url/live/123/9182.ts', $output[8]->getLink());
        $this->assertSame('IT', $output[8]->getCountry());
        $this->assertSame('Boing', $output[8]->getChannel());

        $this->assertSame('http://url/live/123/69548.ts', $output[9]->getLink());
        $this->assertSame('IRL', $output[9]->getCountry());
        $this->assertSame('FM 104', $output[9]->getChannel());

        $this->assertSame('http://url/live/123/50366.ts', $output[10]->getLink());
        $this->assertSame('AU', $output[10]->getCountry());
        $this->assertSame('36 KXAN TX Austin HD US/AU', $output[10]->getChannel());

    }

    public function test_is_valid_data(): void
    {
        $data = <<<'TXT'
http://url/live/123/5058.ts #Name: ======= Italia =======
http://url/live/123/28831.ts #Name: IT: Rai 1 FHD
TXT;

        $this->assertTrue($this->getParser()->isValidData($data));

    }

    public function test_is_invalid_data(): void
    {
        $data = <<<'TXT'
Hello World
TXT;

        $this->assertFalse($this->getParser()->isValidData($data));
    }

}
