<?php

use Symfony\Component\DomCrawler\Crawler;
use SSNepenthe\Hermes\Normalizer\NormalizerStack;
use SSNepenthe\Hermes\Normalizer\NormalizerInterface;

class NormalizerStackTest extends PHPUnit\Framework\TestCase
{
    function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    function it_is_instantiable()
    {
        $normalizer = new NormalizerStack;

        $this->assertInstanceOf(NormalizerInterface::class, $normalizer);
    }

    /** @test */
    function it_can_add_normalizers()
    {
        $normalizer = new NormalizerStack;
        $n1 = Mockery::mock(NormalizerInterface::class);

        $normalizer->addNormalizer($n1);

        $this->assertEquals([$n1], $normalizer->getNormalizers());
    }

    /** @test */
    function it_delegates_to_stack_to_perform_normalization()
    {
        $n1 = Mockery::mock(NormalizerInterface::class)
            ->shouldReceive('normalize')
            ->once()
            ->andReturnUsing(function ($values) {
                return array_map(function ($value) {
                    return $value . ' 1';
                }, $values);
            })
            ->mock();
        $n2 = Mockery::mock(NormalizerInterface::class)
            ->shouldReceive('normalize')
            ->once()
            ->andReturnUsing(function ($values) {
                return array_map(function ($value) {
                    return $value . ' 2';
                }, $values);
            })
            ->mock();
        $crawler = Mockery::mock(Crawler::class);
        $normalizer = new NormalizerStack([$n1, $n2]);

        $this->assertEquals(
            ['normalized 1 2'],
            $normalizer->normalize(['normalized'], $crawler)
        );
    }
}
