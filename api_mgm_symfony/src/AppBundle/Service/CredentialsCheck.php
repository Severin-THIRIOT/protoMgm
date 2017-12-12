<?php

namespace AppBundle\Service;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;


class CredentialsCheck
{

    private $userManager;
    private $securityEncoder;


    public function __construct(UserManagerInterface $userManager, EncoderFactory $securityEncoder){
        $this->userManager = $userManager;
        $this->securityEncoder = $securityEncoder;
    }

    public function checkLogin($username)
    {

        $user = $this->userManager->findUserByUsername($username);

        return $user;
    }

    public function checkPassword($user, $password)
    {
        $factory = $this->securityEncoder;
        $encoder = $factory->getEncoder($user);

        $bool = ($encoder->isPasswordValid($user->getPassword(),$password,$user->getSalt())) ? "true" : "false";

        return $bool;
    }
}