<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function cart(bool $getShared = true): \App\Services\CartService
    {
        if ($getShared) {
            return static::getSharedInstance('cart');
        }

        return new \App\Services\CartService(
            model(\App\Models\CartModel::class),
            model(\App\Models\CartItemModel::class),
            model(\App\Models\ProductModel::class),
            config(\Config\Shop::class),
        );
    }

    public static function checkout(bool $getShared = true): \App\Services\CheckoutService
    {
        if ($getShared) {
            return static::getSharedInstance('checkout');
        }

        return new \App\Services\CheckoutService(
            \Config\Database::connect(),
            static::cart(),
            model(\App\Models\AddressModel::class),
            model(\App\Models\ProductModel::class),
            model(\App\Models\OrderModel::class),
            model(\App\Models\OrderItemModel::class),
            model(\App\Models\PaymentModel::class),
            model(\App\Models\CartItemModel::class),
        );
    }

    public static function orders(bool $getShared = true): \App\Services\OrderService
    {
        if ($getShared) {
            return static::getSharedInstance('orders');
        }

        return new \App\Services\OrderService(
            \Config\Database::connect(),
            model(\App\Models\OrderModel::class),
            model(\App\Models\OrderItemModel::class),
            model(\App\Models\ProductModel::class),
        );
    }

    public static function uploads(bool $getShared = true): \App\Services\UploadService
    {
        if ($getShared) {
            return static::getSharedInstance('uploads');
        }

        return new \App\Services\UploadService(config(\Config\Shop::class));
    }

    public static function payments(bool $getShared = true): \App\Services\PaymentService
    {
        if ($getShared) {
            return static::getSharedInstance('payments');
        }

        return new \App\Services\PaymentService(
            \Config\Database::connect(),
            static::orders(),
            static::uploads(),
            model(\App\Models\OrderModel::class),
            model(\App\Models\PaymentModel::class),
        );
    }

    public static function shipments(bool $getShared = true): \App\Services\ShipmentService
    {
        if ($getShared) {
            return static::getSharedInstance('shipments');
        }

        return new \App\Services\ShipmentService(
            \Config\Database::connect(),
            static::orders(),
            model(\App\Models\OrderModel::class),
            model(\App\Models\ShipmentModel::class),
        );
    }

    public static function reports(bool $getShared = true): \App\Services\ReportService
    {
        if ($getShared) {
            return static::getSharedInstance('reports');
        }

        return new \App\Services\ReportService(\Config\Database::connect());
    }

    public static function auth(bool $getShared = true): \App\Services\AuthService
    {
        if ($getShared) {
            return static::getSharedInstance('auth');
        }

        return new \App\Services\AuthService(model(\App\Models\UserModel::class));
    }

    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
}
