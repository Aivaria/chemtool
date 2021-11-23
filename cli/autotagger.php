<?php
use Chemtool\Doctrine\Entities\Chemical;
use Chemtool\Doctrine\Entities\Tag;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

chdir(dirname(__DIR__));
$container = require 'src/bootstrap.php';

/**
 * @var EntityManager $em
 */
$em = $container->get(EntityManager::class);
include ('data/tags.php');

foreach ($tags as $tag=>$entries){
    $tagObj = $em->getRepository(Tag::class)->findOneBy(['name'=>$tag]);
    foreach ($entries as $entry){
        $chemical = $em->getRepository(Chemical::class)->findOneBy(['name'=>$entry]);
        if($chemical==null){
            $chemical = $em->getRepository(Chemical::class)->findOneBy(['idName'=>mb_strtolower(str_replace(' ','_',$entry))]);
        }
        if($chemical==null){
            var_dump($entry);
        }
        else {
            try {
                $chemical->addTag($tagObj);
                $em->flush($chemical);
            }
            catch (\Exception $ex){
                var_dump($ex->getMessage());
            }
        }
    }
}