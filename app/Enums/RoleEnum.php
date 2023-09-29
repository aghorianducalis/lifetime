<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'admin';
    case User = 'user';

    public function permissions(): array
    {
        return PermissionEnum::permissionsFromRoleEnum($this);
    }

    public static function rolesOfPermission(PermissionEnum $permissionEnum): array
    {
        return match($permissionEnum) {
            PermissionEnum::CreateUser,
            PermissionEnum::UpdateUser,
            PermissionEnum::DeleteUser,
            PermissionEnum::ViewUser,
            PermissionEnum::ViewUsers => [
                self::Admin,
            ],
            PermissionEnum::CreateCoordinate,
            PermissionEnum::UpdateCoordinate,
            PermissionEnum::DeleteCoordinate,
            PermissionEnum::ViewCoordinate,
            PermissionEnum::ViewCoordinates,
            PermissionEnum::CreateEvent,
            PermissionEnum::UpdateEvent,
            PermissionEnum::DeleteEvent,
            PermissionEnum::ViewEvent,
            PermissionEnum::ViewEvents,
            PermissionEnum::CreateLocation,
            PermissionEnum::UpdateLocation,
            PermissionEnum::DeleteLocation,
            PermissionEnum::ViewLocation,
            PermissionEnum::ViewLocations,
            PermissionEnum::CreateResource,
            PermissionEnum::UpdateResource,
            PermissionEnum::DeleteResource,
            PermissionEnum::ViewResource,
            PermissionEnum::ViewResources,
            PermissionEnum::CreateResourceType,
            PermissionEnum::UpdateResourceType,
            PermissionEnum::DeleteResourceType,
            PermissionEnum::ViewResourceType,
            PermissionEnum::ViewResourceTypes => [
                self::User,
            ],
        };
    }
}
