<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Matcher\ClosureMatcher;
use SSNepenthe\Hermes\Matcher\MatcherInterface;

class ClosureMatcherTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $matcher = new ClosureMatcher(function () {});

        $this->assertInstanceOf(MatcherInterface::class, $matcher);
    }

    /** @test */
    function it_delegates_to_the_passed_closure_to_perform_match()
    {
        $crawler = Mockery::mock(Crawler::class);
        $matcher = new ClosureMatcher(function ($crawler) {
            return true;
        });

        $this->assertTrue($matcher->matches($crawler));
    }
}
