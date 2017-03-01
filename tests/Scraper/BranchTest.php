<?php

use SSNepenthe\Hermes\Scraper\Root;
use SSNepenthe\Hermes\Scraper\Branch;
use SSNepenthe\Hermes\Matcher\MatcherInterface;
use SSNepenthe\Hermes\Scraper\ScraperInterface;

class BranchTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = Mockery::mock(MatcherInterface::class);
        $scraper = new Branch('name', $matcher);

        $this->assertInstanceOf(ScraperInterface::class, $scraper);
        $this->assertInstanceOf(Root::class, $scraper);
    }
}
