<?php

namespace spec\NoahGlaser\EntityBundle\Event;

use PhpSpec\ObjectBehavior;
use NoahGlaser\EntityBundle\Event\SaveFileSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Prophecy\Prophet;
use NoahGlaser\EntityBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Prophecy\Argument;


class FileSubscriberSpec extends ObjectBehavior
{
    private $prophet;
    
    function let()
    {
        $this->beConstructedWith('path');
        $this->prophet = new \Prophecy\Prophet();
    }

    
    
    function it_should_have_a_subscriber_method_return_array_of_events()
    {
        $this->getSubscribers()->shouldReturn(['prePersist', 'preUpdate', 'postPersist', 'postUpdate','preRemove','postRemove']);
    }
    
   
    
    function it_should_have_kernel_root()
    {
        $this->getKernel()->shouldBe('path');        
    }
    
    function it_should_set_entity_for_file_subclass()
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $abstractFileClass = $this->prophet->prophesize();
        $abstractFileClass->willExtend('NoahGlaser\EntityBundle\Entity\File');
        $args->getObject()->willReturn($abstractFileClass);
        $this->processEntity($args->reveal());
        $this->getEntity()->shouldReturnAnInstanceOf('NoahGlaser\EntityBundle\Entity\File');
    }
    
    function it_should_not_set_entity_for_non_file_subclass()
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $abstractFileClass = $this->prophet->prophesize('stdClass');
        $args->getObject()->willReturn($abstractFileClass);
        $this->processEntity($args->reveal());
        $this->getEntity()->shouldReturn(null);
    }
    
    function it_should_throw_error_when_process_entity_gets_non_life_cycle_event()
    {
        $this->shouldThrow('InvalidArgumentException')->duringProcessEntity('moo');
    }
    
    function helper_should_not_set_path_for_x_when_a_file_is_not_present($method)
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        $file->getAbsolutePath(Argument::type('string'))->willReturn(null);
        $file->getUploadRootDir(Argument::type('string'))->willReturn(null);
        $file->getFile()->willReturn(null);
        $file->setPath(Argument::type('string'))->willReturn($file);
        $args->getObject()->willReturn($file);
        if($method === "prePersist")
        {
            $this->prePersist($args);            
        }
        
        if($method === "preUpdate")
        {
            $this->preUpdate($args);
        }
        
        $file->setPath(Argument::type('string'))->shouldNotHaveBeenCalled();

    }
   
    function it_should_not_set_path_for_preperist_when_a_file_is_not_present()
    {
        $this->helper_should_not_set_path_for_x_when_a_file_is_not_present('prePersist');
    }
       
    function it_should_not_set_path_for_preupdate_when_a_file_is_not_present()
    {
        $this->helper_should_not_set_path_for_x_when_a_file_is_not_present('preUpdate');
    }


         
    function helper_should_set_path_for_pre_x_when_file_is_present($method)
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        
        $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        $file->getAbsolutePath(Argument::type('string'))->willReturn(null);
        $file->getUploadRootDir(Argument::type('string'))->willReturn(null);
        $file->setPath(Argument::type('string'))->willReturn($file);

        $uploadedFile = $this->prophet->prophesize();
        $uploadedFile->willImplement('spec\NoahGlaser\EntityBundle\Event\UploadedFileInterface');
        $uploadedFile->guessExtension()->willReturn('jpg');
        
        $file->getFile()->willReturn($uploadedFile);
        $args->getObject()->willReturn($file);
        
        if($method === "prePersist")
        {
            $this->prePersist($args);
        }
        
        if($method === "preUpdate")
        {
            $this->preUpdate($args);
        }
        
        $file->setPath(Argument::type('string'))->shouldHaveBeenCalled();

    }
    
    function it_should_set_path_for_pre_preupdate_when_file_is_present()
    {
        $this->helper_should_set_path_for_pre_x_when_file_is_present('preUpdate');
    }
    
    function it_should_set_path_for_pre_preperists_when_file_is_present()
    {
        $this->helper_should_set_path_for_pre_x_when_file_is_present('prePersist');        
    }
    
    function helper_run_preupdate_setup($path)
    {
                
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
         $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        //USE __FILE__ to get around is_file function.
        $file->getAbsolutePath(Argument::type('string'))->willReturn($path );
        $file->getUploadRootDir(Argument::type('string'))->willReturn(get_class() . ".php");
        $file->setPath(Argument::type('string'))->willReturn($file);
        $file->getPath()->willReturn($path);
        $uploadedFile = $this->prophet->prophesize();
        $uploadedFile->willImplement('spec\NoahGlaser\EntityBundle\Event\UploadedFileInterface');
        $uploadedFile->guessExtension()->willReturn('jpg');
        $uploadedFile->move(Argument::type('string'), Argument::type('string'))->willReturn(true);
        
        $file->getFile()->willReturn($uploadedFile);
        $file->setFile(Argument::any())->willReturn($file);
        
        $args->getObject()->willReturn($file);
        $this->preUpdate($args);
        return $args;
    }
    
    function it_should_set_temp_variable_for_updating_file_where_old_and_new_file_exists()
    {
        
        $this->helper_run_preupdate_setup(__FILE__);
        $this->getTemp()->shouldReturn(__FILE__ );

    }
    
        
    function it_should_delete_old_file_if_file_exists()
    {
        $path = __DIR__ . '\bad.txt';
        file_put_contents($path, 'this is bad');
        $this->helper_run_preupdate_setup($path);
        $this->deleteOldFile()->shouldReturn(true);        
    }
    
    function it_should_set_temp_variable_to_null()
    {
        $path = __DIR__ . '\bad.txt';
        file_put_contents($path, 'this is bad');
        $args = $this->helper_run_preupdate_setup($path);
        $this->postUpdate($args);
        $this->getTemp()->shouldReturn(null);
    }
    
    
    function it_should_move_file_if_file_exists_for_upload()
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        //USE __FILE__ to get around is_file function.
        $file->getAbsolutePath(Argument::type('string'))->willReturn('path/' );
        $file->getUploadRootDir(Argument::type('string'))->willReturn(get_class() . ".php");
        $file->setPath(Argument::type('string'))->willReturn($file);
        $file->getPath()->willReturn('path.jpg');
        $uploadedFile = $this->prophet->prophesize();
        $uploadedFile->willImplement('spec\NoahGlaser\EntityBundle\Event\UploadedFileInterface');
        $uploadedFile->guessExtension()->willReturn('jpg');
        $uploadedFile->move(Argument::type('string'), Argument::type('string'))->willReturn(true);
        
        $file->getFile()->willReturn($uploadedFile);
        $file->setFile(Argument::any())->willReturn($file);
        
        $args->getObject()->willReturn($file);
        $this->preUpdate($args);
        $this->postUpdate($args);
        $uploadedFile->move(Argument::type('string'), Argument::type('string'))->shouldHaveBeenCalled();
 
    }
    
    
    function it_should_set_temp_variable_on_pre_remove()
    {
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        $file->getAbsolutePath(Argument::type('string'))->willReturn('path/path.jpg' );
        $args->getObject()->willReturn($file);
        $this->preRemove($args);
        $this->getTemp()->shouldReturn('path/path.jpg');
    }
     
    
    function it_should_remove_temp_file_if_it_exists()
    {
        $path = __DIR__ . '\bad.txt';
        file_put_contents($path, 'this is bad');
        $args = $this->prophet->prophesize('Doctrine\ORM\Event\LifecycleEventArgs');
        $file = $this->prophet->prophesize('NoahGlaser\EntityBundle\Entity\File');
        $file->getAbsolutePath(Argument::type('string'))->willReturn($path);
        $args->getObject()->willReturn($file);

        $this->setTemp($path);
        $this->postRemove($args)->shouldReturn(true);
    }
    
    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }
    
    
}

//This is done to prevent the fake error with mocking a file upload
interface UploadedFileInterface
{
    public function guessExtension();
    public function move($path, $filename);
}