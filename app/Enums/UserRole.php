<?php 

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'administrator';
    case MANAGER = 'manager';
    case USER = 'user';
}