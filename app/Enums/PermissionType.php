<?php

namespace App\Enums;

enum PermissionType: string
{
    case VIEW_USERS = 'view-users';
    case CREATE_USERS = 'create-users';
    case EDIT_USERS = 'edit-users';
    case DELETE_USERS = 'delete-users';

    case VIEW_PRODUCTS = 'view-products';
    case CREATE_PRODUCTS = 'create-products';
    case EDIT_PRODUCTS = 'edit-products';
    case DELETE_PRODUCTS = 'delete-products';

    case VIEW_CATEGORIES = 'view-categories';
    case CREATE_CATEGORIES = 'create-categories';
    case EDIT_CATEGORIES = 'edit-categories';
    case DELETE_CATEGORIES = 'delete-categories';

    case VIEW_COLORS = 'view-colors';
    case CREATE_COLORS = 'create-colors';
    case EDIT_COLORS = 'edit-colors';
    case DELETE_COLORS = 'delete-colors';

    case VIEW_SIZES = 'view-sizes';
    case CREATE_SIZES = 'create-sizes';
    case EDIT_SIZES = 'edit-sizes';
    case DELETE_SIZES = 'delete-sizes';

    case VIEW_CARTS = 'view-carts';
    case CREATE_CARTS = 'create-carts';
    case EDIT_CARTS = 'edit-carts';
    case DELETE_CARTS = 'delete-carts';

    case VIEW_ORDERS = 'view-orders';
    case CREATE_ORDERS = 'create-orders';
    case EDIT_ORDERS = 'edit-orders';
    case DELETE_ORDERS = 'delete-orders';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }


}