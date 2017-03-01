<?php

use function SSNepenthe\Hermes\result_return_value;

class FunctionsTest extends PHPUnit\Framework\TestCase
{
    /** @test */
    function it_grabs_proper_return_value_from_result_array()
    {
        // Empty string in place of empty array.
        $this->assertEquals('', result_return_value([]));

        // Allows default override.
        $this->assertEquals('default', result_return_value([], 'default'));

        // First value if count == 1.
        $this->assertEquals('one', result_return_value(['one']));

        // Full array otherwise.
        $this->assertEquals(
            ['one', 'two', 'three'],
            result_return_value(['one', 'two', 'three'])
        );
    }
}
