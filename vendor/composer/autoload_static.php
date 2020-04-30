<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1ae03e0cf602e3fd49bb3e6969c109a1
{
    public static $prefixLengthsPsr4 = array (
        'A' =>
        array (
            'Apex\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Apex\\' =>
        array (
            0 => __DIR__ . '/../..' . '/apex',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1ae03e0cf602e3fd49bb3e6969c109a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1ae03e0cf602e3fd49bb3e6969c109a1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
