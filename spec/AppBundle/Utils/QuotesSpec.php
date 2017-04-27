<?php

namespace spec\AppBundle\Utils;

use AppBundle\Utils\Quotes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QuotesSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Quotes::class);
    }

    function it_should_return_example_quote()
    {
        $this->getQuote()->shouldReturn('Stay hungry, stay foolish');
    }
}
