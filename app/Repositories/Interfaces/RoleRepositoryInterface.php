<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function getRolesWithPermissions(): Collection;
}
