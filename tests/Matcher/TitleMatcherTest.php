<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\TitleMatcher;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

class TitleMatcherTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = new TitleMatcher('irrelevant');

        $this->assertInstanceOf(MatcherInterface::class, $matcher);
    }

    /** @test */
    function it_can_match_a_regular_expression()
    {
        $firstCrawler = Mockery::mock(Crawler::class)
            ->shouldReceive('text')
            ->twice()
            ->andReturn('abb', 'bcc')
            ->mock();
        $filterCrawler = Mockery::mock(Crawler::class)
            ->shouldReceive('first')
            ->twice()
            ->andReturn($firstCrawler)
            ->mock();
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('filter')
            ->twice()
            ->andReturn($filterCrawler)
            ->mock();
        $matcher = new TitleMatcher('/b{2,}/');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }

    /** @test */
    function it_can_match_a_string()
    {
        $firstCrawler = Mockery::mock(Crawler::class)
            ->shouldReceive('text')
            ->twice()
            ->andReturn('abb', 'bcc')
            ->mock();
        $filterCrawler = Mockery::mock(Crawler::class)
            ->shouldReceive('first')
            ->twice()
            ->andReturn($firstCrawler)
            ->mock();
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('filter')
            ->twice()
            ->andReturn($filterCrawler)
            ->mock();
        $matcher = new TitleMatcher('bb');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }
}
