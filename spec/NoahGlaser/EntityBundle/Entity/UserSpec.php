<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace spec\NoahGlaser\EntityBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use NoahGlaser\EntityBundle\Entity\User;


/**
 * Description of UserSpec
 *
 * @author student
 */
class UserSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\NoahGlaser\EntityBundle\Entity\BaseUserTest');
    }
    
    function it_have_getters_setter_for_base_properties()
    {
        $this->setEmail('email');
        $this->getEmail()->shouldBeLike('email');
        
        $this->setPassword('password');
        $this->getPassword()->shouldBeLike('password');
        
        $this->setIsActive(true);
        $this->getIsActive()->shouldBeLike(true);

        $this->setRoles(['ROLE_USER']);
        $this->getRoles()->shouldBeLike(['ROLE_USER']);

        $this->setSecretCode('secret');
        $this->getSecretCode()->shouldBeLike('secret');

    }
    
    function it_should_set_return_is_active_when_is_enabled_is_called()
    {
        $this->setIsActive(true);
        $this->isEnabled()->shouldBeLike(true);
    }
    
    function it_get_salt_should_return_null()
    {
        $this->getSalt()->shouldBeLike(null);
    }
    
    function it_get_username_should_return_email()
    {
        $this->setEmail('email');
        $this->getUsername()->shouldBeLike('email');
    }
    
    function it_should_erase_credentials_return_null()
    {
        $this->eraseCredentials()->shouldBeLike(null);
    }
    
    function it_should_return_true_for_isAccountNonExpired_isAccountNonLocked_isCredentialsNonExpired()
    {
        $this->isAccountNonExpired()->shouldBeLike(true);
        $this->isAccountNonLocked()->shouldBeLike(true);
        $this->isCredentialsNonExpired()->shouldBeLike(true);
    }
}

class UserTest extends User
{
    //put your code here
}
