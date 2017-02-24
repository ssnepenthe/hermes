<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\HostMatcher;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

class HostMatcherTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = new HostMatcher('irrelevant');

        $this->assertInstanceOf(MatcherInterface::class, $matcher);
    }

    /** @test */
    function it_can_match_a_regular_expression()
    {
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->twice()
            ->andReturn(
                'https://www.google.com/#q=test',
                'https://search.yahoo.com/search?p=test'
            )
            ->mock();
        $matcher = new HostMatcher('/go+gle\.com/');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }

    /** @test */
    function it_can_match_a_string()
    {
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('getUri')
            ->twice()
            ->andReturn(
                'https://www.google.com/#q=test',
                'https://search.yahoo.com/search?p=test'
            )
            ->mock();
        $matcher = new HostMatcher('google.com');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }
}
