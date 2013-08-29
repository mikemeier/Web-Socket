<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;
use mikemeier\ConsoleGame\DependencyInjection\Container;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use mikemeier\ConsoleGame\Output\Line\Decorator\UserDecorator;
use mikemeier\ConsoleGame\Output\Line\Decorator\PathDecorator;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$entityPaths = array(__DIR__.'/../src/mikemeier/ConsoleGame');
$config = Setup::createAnnotationMetadataConfiguration($entityPaths, true, __DIR__.'/../cache/proxies', null, false);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../cache/db.sqlite',
);

$container = new Container(array(
    'em' => EntityManager::create($conn, $config),
    'loader' => $loader,
    'decorators' => array(
        new UserDecorator(100),
        new PathDecorator(50)
    )
));

/**
 * Commands
 */
$finder = new Finder();
$finder->files()->name('*Command.php')->in(__DIR__.'/../src/mikemeier/ConsoleGame/Command');
$prefix = 'mikemeier\\ConsoleGame\\Command';

/** @var SplFileInfo $file */
foreach($finder as $file){
    $ns = $prefix;
    if($relativePath = $file->getRelativePath()){
        $ns .= '\\'.strtr($relativePath, '/', '\\');
    }
    $r = new \ReflectionClass($ns.'\\'.$file->getBasename('.php'));
    if($r->implementsInterface('mikemeier\\ConsoleGame\\Command\\CommandInterface') && $r->isInstantiable()){
        $commands[] = $r->newInstance()->setContainer($container);
    }
}

$container->set('commands', $commands);

return $container;