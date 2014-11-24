<?php

/*
 * Read Cookbook to find out more about this interface.  We 
 */
namespace NoahGlaser\EntityBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 *
 * @author Owner
 */
interface FileInterface 
{
    /**
     * @return UploadedFile Gets File that is being uploaded
     */
    public function getFile();
    /**
     * sets the file being uploaded
     * @param UploadedFile $file
     * 
     */
    public function setFile(UploadedFile $file);
    /**
     * @param string $root_dir This is the root directory variable
     * @return string the absolute directory path where uploaded documents should be saved
     */
    public function getUploadRootDir($root_dir);
    
    /**
     * @return string return the path of the folder to upload the file to minus 
     */
    public function getUploadDir();
}
