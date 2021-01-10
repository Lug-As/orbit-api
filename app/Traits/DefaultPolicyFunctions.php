<?php


namespace App\Traits;


trait DefaultPolicyFunctions
{
    use AllowsAllForAdmins, AllowsForOwner;
}
