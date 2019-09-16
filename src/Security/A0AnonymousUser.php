<?php

namespace App\Security;


class A0AnonymousUser extends A0User {

    public function __construct()
    {
        parent::__construct(null,array('IS_AUTHENTICATED_ANONYMOUSLY'));
    }

    public function getUsername()
    {
        return null;
    }

} 