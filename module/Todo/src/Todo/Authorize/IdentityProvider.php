<?php

namespace Todo\Authorize;

use BjyAuthorize\Provider\Identity\ProviderInterface;

class IdentityProvider implements ProviderInterface
{

    protected $userService;
    protected $defaultRole;

    public function getIdentityRoles()
    {
        $authService = $this->userService->getAuthService();

        if (!$authService->hasIdentity()) {
            // get default/guest role
            return $this->getDefaultRole();
        } else {
            // get roles associated with the logged in user
            $roles = $authService->getIdentity()->getRoles();
            return $roles;
        }
    }

    public function getUserService()
    {
        return $this->userService;
    }

    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    public function setDefaultRole($defaultRole)
    {
        $this->defaultRole = $defaultRole;
        return $this;
    }

}
