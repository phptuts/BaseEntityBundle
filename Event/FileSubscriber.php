<?php

namespace NoahGlaser\EntityBundle\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use NoahGlaser\EntityBundle\Entity\File;
use Symfony\Component\HttpKernel\Kernel;
use Doctrine\Common\EventSubscriber;

class FileSubscriber implements EventSubscriber
{
    protected  $kernel_root;
    
    private $temp;

    /**
     *
     * @var File
     */
    private $entity;

    public function __construct($kernel_root) 
    {
        $this->kernel_root = $kernel_root;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist', 
            'preUpdate', 
            'postPersist', 
            'postUpdate',
            'preRemove',
            'postRemove'
        ];
    }
    

    public function preSave()
    {
        // TODO: write logic here
    }

    public function getKernel()
    {
        return $this->kernel_root;
    }

    public function processEntity( $args)
    {
        if(!$args instanceof LifecycleEventArgs)
        {
            throw new \InvalidArgumentException("args must be an instance of Lifecycleeventargs");
        }
        
        $entity = $args->getObject();
        if(is_subclass_of($entity, 'NoahGlaser\EntityBundle\Entity\File'))
        {
              $this->entity = $entity;
        }
      
    }

    
    public function getTemp()
    {
        return $this->temp;
    }
    
    public function setTemp()
    {
        $this->temp = null;
    }
    
    public function getEntity()
    {
        return $this->entity;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->preUpload($args);
    }
    
    public function checkToSeeIfFileExists()
    {
       return  is_file($this->getEntity()->getAbsolutePath($this->kernel_root));
    }
    
    public function preUpload(LifecycleEventArgs $args)
    {
        $this->processEntity($args);
        // check if we have an old image path
        if ($this->checkToSeeIfFileExists()) 
        {
            // store the old name to delete after the update
            $this->temp = $this->getEntity()->getAbsolutePath($this->kernel_root);
        } 
        
        
        if (null !== $this->entity->getFile()) 
        {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->entity->setPath($filename.'.'. $this->entity->getFile()->guessExtension());
        }
    }
    
    public function upload()
    {
        
      if($this->getEntity()->getFile() === null)
      {
          return false;
      }
        
       if($this->getTemp() !== null)
       {
           $this->deleteOldFile();
       }
                  
       $this->setTemp(null);

               
        
        $this->getEntity()->getFile()->move(
            $this->getEntity()->getUploadRootDir($this->kernel_root),
            $this->getEntity()->getPath()
        );

        $this->getEntity()->setFile(null);

       
    }

    public function deleteOldFile()
    {
        if(file_exists($this->getTemp()))
        {
           return unlink($this->getTemp());
        }
        return false;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->processEntity($args);
        $this->upload();
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }
    
    

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->processEntity($args);
        $this->temp = $this->getEntity()->getAbsolutePath($this->kernel_root);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->processEntity($args);
        
        if(file_exists($this->getEntity()->getAbsolutePath($this->kernel_root)))
        {
            return unlink($this->getEntity()->getAbsolutePath($this->kernel_root));
        }
        
        return false;
    }
}
