<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'security.user.provider.concrete.in_database' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserProviderInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\doctrine-bridge\\Security\\User\\EntityUserProvider.php';

return $this->privates['security.user.provider.concrete.in_database'] = new \Symfony\Bridge\Doctrine\Security\User\EntityUserProvider(($this->services['doctrine'] ?? $this->getDoctrineService()), 'App\\Entity\\CeUser', 'ceEmail', NULL);
