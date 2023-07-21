<?php
namespace App\Enums;

enum UserRoleEnum: string
{
    case USER = 'User';
    case GROUP_ADMIN = 'Group Admin';
    case SUPER_ADMIN = 'Super Admin';
}
