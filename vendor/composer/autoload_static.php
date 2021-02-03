<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8a6e1b6e49550bb86dbd66ce15210edf
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8a6e1b6e49550bb86dbd66ce15210edf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8a6e1b6e49550bb86dbd66ce15210edf::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8a6e1b6e49550bb86dbd66ce15210edf::$classMap;

        }, null, ClassLoader::class);
    }
}