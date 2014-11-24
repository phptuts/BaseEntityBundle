<?php

namespace spec\NoahGlaser\EntityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use NoahGlaser\EntityBundle\Entity\File;

class FileSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\NoahGlaser\EntityBundle\Entity\FileTest');
    }
    
    function it_should_get_web_path()
    {
        $this->setPath('pic.jpg');
        $this->getWebPath()->shouldReturn('uploads/pics/pic.jpg');
    }
    
    function it_should_get_absolute_path()
    {
        $this->setPath('pic.jpg');
        $this->getAbsolutePath("root/path/web/")->shouldReturn('root/path/web/uploads/pics/pic.jpg');
    }
    
    function it_should_get_picture_dir()
    {
        $this->getUploadRootDir("root/path/web/")->shouldReturn('root/path/web/uploads/pics');
    }
    
    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }
}


class FileTest extends File
{
    public function getUploadDir() 
    {
        return 'uploads/pics';
    }
}