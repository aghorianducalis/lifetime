<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case CreateCoordinate = 'create_coordinate';
    case UpdateCoordinate = 'update_coordinate';
    case DeleteCoordinate = 'delete_coordinate';
    case ViewCoordinate = 'view_coordinate';
    case ViewCoordinates = 'view_coordinates';
    case CreateEvent = 'create_event';
    case UpdateEvent = 'update_event';
    case DeleteEvent = 'delete_event';
    case ViewEvent = 'view_event';
    case ViewEvents = 'view_events';
    case CreateLocation = 'create_location';
    case UpdateLocation = 'update_location';
    case DeleteLocation = 'delete_location';
    case ViewLocation = 'view_location';
    case ViewLocations = 'view_locations';
    case CreateResource = 'create_resource';
    case UpdateResource = 'update_resource';
    case DeleteResource = 'delete_resource';
    case ViewResource = 'view_resource';
    case ViewResources = 'view_resources';
    case CreateResourceType = 'create_resource_type';
    case UpdateResourceType = 'update_resource_type';
    case DeleteResourceType = 'delete_resource_type';
    case ViewResourceType = 'view_resource_type';
    case ViewResourceTypes = 'view_resource_types';
    case CreateUser = 'create_user';
    case UpdateUser = 'update_user';
    case DeleteUser = 'delete_user';
    case ViewUser = 'view_user';
    case ViewUsers = 'view_users';

    public static function permissionsFromRoleEnum(RoleEnum $roleEnum): array
    {
        return match($roleEnum) {
            RoleEnum::Admin => [
                self::CreateUser,
                self::UpdateUser,
                self::DeleteUser,
                self::ViewUser,
                self::ViewUsers,
            ],
            RoleEnum::User => [
                self::CreateCoordinate,
                self::UpdateCoordinate,
                self::DeleteCoordinate,
                self::ViewCoordinate,
                self::ViewCoordinates,
                self::CreateEvent,
                self::UpdateEvent,
                self::DeleteEvent,
                self::ViewEvent,
                self::ViewEvents,
                self::CreateLocation,
                self::UpdateLocation,
                self::DeleteLocation,
                self::ViewLocation,
                self::ViewLocations,
                self::CreateResource,
                self::UpdateResource,
                self::DeleteResource,
                self::ViewResource,
                self::ViewResources,
                self::CreateResourceType,
                self::UpdateResourceType,
                self::DeleteResourceType,
                self::ViewResourceType,
                self::ViewResourceTypes,
            ],
        };
    }
}
