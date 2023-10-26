<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba5cb10f1163be48175f5a01aa453e28
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WooZoneVendor\\Campo\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WooZoneVendor\\Campo\\' => 
        array (
            0 => __DIR__ . '/..' . '/campo/random-user-agent/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba5cb10f1163be48175f5a01aa453e28::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba5cb10f1163be48175f5a01aa453e28::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
