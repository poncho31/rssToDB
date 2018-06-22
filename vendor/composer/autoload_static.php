<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3416122ac82a39326f2ef30d5031b196
{
    public static $classMap = array (
        'MySQLDump' => __DIR__ . '/..' . '/dg/mysql-dump/src/MySQLDump.php',
        'MySQLImport' => __DIR__ . '/..' . '/dg/mysql-dump/src/MySQLImport.php',
        'Poncho\\Database' => __DIR__ . '/../..' . '/src/Database.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit3416122ac82a39326f2ef30d5031b196::$classMap;

        }, null, ClassLoader::class);
    }
}
