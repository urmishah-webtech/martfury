<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9b5f894ae883e66bf646b4c4915ecfeb
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'HitPay\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'HitPay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9b5f894ae883e66bf646b4c4915ecfeb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9b5f894ae883e66bf646b4c4915ecfeb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9b5f894ae883e66bf646b4c4915ecfeb::$classMap;

        }, null, ClassLoader::class);
    }
}
