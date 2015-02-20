<?php

namespace NoahGlaser\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/** 
 * @ORM\MappedSuperclass()
 */
abstract class File extends Base implements FileInterface
{

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;

    /**
     * @var UploadedFile  
     */
    protected $file;

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        return $this;

    }
    
    public function getAbsolutePath($root_dir)
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir($root_dir).'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }
    
    public function getUploadRootDir($root_dir) 
    {
        return  $root_dir . $this->getUploadDir();
    }
        

    
    
    
}
