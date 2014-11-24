<?php

namespace spec\NoahGlaser\EntityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use NoahGlaser\EntityBundle\Entity\Base;

class BaseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\NoahGlaser\EntityBundle\Entity\BaseTest');
    }
    
    function it_should_set_createdAt_current_datetime_on_create_lifecyle_event()
    {
        $this->setCreatedAtValue();
        $this->getCreatedAt()->shouldBeLike(new \DateTime());
    }
    
    function it_should_set_updatedAt_current_datetime_on_create_lifecyle_event()
    {
        $this->setCreatedAtValue();
        $this->getUpdatedAt()->shouldBeLike(new \DateTime());
    }
    
    function it_should_set_updatedAt_new_datetime_on_update_lifecyle_event()
    {
        $this->setUpdatedAtValue();
        $this->getUpdatedAt()->shouldBeLike(new \DateTime());
    }
    
    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }
}

class BaseTest extends Base
{
    //put your code here
}

