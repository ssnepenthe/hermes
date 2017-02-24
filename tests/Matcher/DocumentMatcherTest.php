<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\DocumentMatcher;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

class DocumentMatcherTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = new DocumentMatcher('irrelevant');

        $this->assertInstanceOf(MatcherInterface::class, $matcher);
    }

    /** @test */
    function it_can_match_a_regular_expression()
    {
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('html')
            ->twice()
            ->andReturn('abb', 'bcc')
            ->mock();
        $matcher = new DocumentMatcher('/b{2,}/');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }

    /** @test */
    function it_can_match_a_string()
    {
        $crawler = Mockery::mock(Crawler::class)
            ->shouldReceive('html')
            ->twice()
            ->andReturn('abb', 'bcc')
            ->mock();
        $matcher = new DocumentMatcher('bb');

        $this->assertTrue($matcher->matches($crawler));
        $this->assertFalse($matcher->matches($crawler));
    }
}
