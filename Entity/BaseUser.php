<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace NoahGlaser\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Description of User
 *
 * @author student
 */

/**
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class BaseUser extends Base implements AdvancedUserInterface
{
   /**
    * @ORM\Column(name="email", type="string", length=255, unique=true)
    * @var string
    */
   private $email;
   
   /**
    * @ORM\Column(name="password", type="string", length=255)
    * @var string
    */
   private $password;
      
   /**
    * @ORM\Column(name="isActive", type="boolean")
    * @var string
    */
   private $isActive;
   
   /**
    * @var array
    *
    * @ORM\Column(name="roles", type="array")
    */
   private $roles;
   
   /**
    * @var string
    * 
    * @ORM\Column(name="secret_code", type="string", nullable=true)
    */
   private $secretCode;
   
   
   public function getEmail() 
   {
      return $this->email;
   }

   public function getPassword()
   {
      return $this->password;
   }

   public function getIsActive() 
   {
      return $this->isActive;
   }

   public function getRoles() 
   {
      return $this->roles;
   }

   public function getSecretCode() 
   {
      return $this->secretCode;
   }

   public function setEmail($email) 
   {
      $this->email = $email;
   }

   public function setPassword($password) 
   {
      $this->password = $password;
   }

   public function setIsActive($isActive) 
   {
      $this->isActive = $isActive;
   }

   public function setRoles($roles) 
   {
      $this->roles = $roles;
   }

   public function setSecretCode($secretCode) 
   {
      $this->secretCode = $secretCode;
   }

   public function eraseCredentials()
   {
      ;
   }

   public function getSalt()
   {
      return null;
   }

   public function getUsername()
   {
      return $this->email;
   }

   public function isAccountNonExpired()
   {
      return true;
   }

   public function isAccountNonLocked() 
   {
      return true;
   }

   public function isCredentialsNonExpired() 
   {
      return true;
   }

   public function isEnabled() 
   {
      return $this->isActive;
   }

}
