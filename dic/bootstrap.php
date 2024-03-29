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
use mikemeier\ConsoleGame\Command\Helper\ContainerHelper;
use mikemeier\ConsoleGame\Command\Helper\EnvironmentHelper;
use mikemeier\ConsoleGame\Command\Helper\EntityManagerHelper;
use mikemeier\ConsoleGame\Command\Helper\FeedbackHelper;
use mikemeier\ConsoleGame\Command\Helper\RepositoryHelper;
use mikemeier\ConsoleGame\Command\Helper\UserHelper;
use mikemeier\ConsoleGame\Command\Helper\RouterHelper;
use mikemeier\ConsoleGame\Network\Service\InternetRouter;
use mikemeier\ConsoleGame\Network\Service\Isp;
use mikemeier\ConsoleGame\Network\Service\Dhcp;
use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Command\Helper\LoopHelper;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$entityPaths = array(__DIR__.'/../src/mikemeier/ConsoleGame');
$config = Setup::createAnnotationMetadataConfiguration($entityPaths, true, __DIR__.'/../cache/proxies', null, false);

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../cache/db.sqlite',
);

$em         = EntityManager::create($conn, $config);
$router     = new InternetRouter(
    new Isp(
        new Dhcp('213.150.0.0', '220.255.255.255'),
        new Dns()
    ),
    new Dhcp('10.0.0.0', '10.255.255.255'),
    new Dns()
);

$container = new Container(array(
    'em' => $em,
    'loader' => $loader,
    'decorators' => array(
        new UserDecorator(100),
        new PathDecorator(50)
    )
));

$helpers = array(
    new ContainerHelper($container),
    new EnvironmentHelper(),
    new EntityManagerHelper($em),
    new FeedbackHelper(),
    new RepositoryHelper($em),
    new UserHelper(),
    new RouterHelper($router),
    new LoopHelper(function()use($container){
        return $container->get('loop');
    })
);

/**
 * Commands
 */
$finder = new Finder();
$finder->files()->name('*Command.php')->in(__DIR__.'/../src/mikemeier/ConsoleGame/Command/Concrete');
$prefix = 'mikemeier\\ConsoleGame\\Command\\Concrete';

/** @var SplFileInfo $file */
foreach($finder as $file){
    $ns = $prefix;
    if($relativePath = $file->getRelativePath()){
        $ns .= '\\'.strtr($relativePath, '/', '\\');
    }
    $r = new \ReflectionClass($ns.'\\'.$file->getBasename('.php'));
    if($r->implementsInterface('mikemeier\\ConsoleGame\\Command\\CommandInterface') && $r->isInstantiable()){
        $commands[] = $r->newInstance()->setHelpers($helpers);
    }
}

$container->set('commands', $commands);

return $container;