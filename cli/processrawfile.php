<?php


use Chemtool\Doctrine\Entities\Chemical;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

chdir(dirname(__DIR__));
$container = require 'src/bootstrap.php';

/**
 * @var EntityManager $em
 */
$em = $container->get(EntityManager::class);

$file = file_get_contents("data/rawfile2");
$chems = explode("\r\n\r\n", $file);
echo "Processor: start\n";
foreach ($chems as $chem) {
    $chemical = new Chemical();
    $entries = explode("\r\n", $chem);

    foreach ($entries as $entry) {
        $entry = trim($entry);
        if (strstr($entry, "id =")) {
            $idname = trim(str_replace('"', "", explode("=", $entry)[1]));
            echo "Processor: searching for {$idname}\n";
            $chemical = $em->getRepository(Chemical::class)->findOneBy(['idName' => $idname]);
            break;
        }
    }
    if ($chemical == null) {
        echo "Processor: not found, creating new\n";
        $chemical = new Chemical();
    }
    $em->persist($chemical);
    echo "Processor: processing entries\n";

    foreach ($entries as $entry) {
        $entry = trim($entry);
        if (strstr($entry, "id =")) {
            $idname = trim(str_replace('"', "", explode("=", $entry)[1]));
            echo "Processor: setting idname to {$idname}\n";
            $chemical->setIdName($idname);
        }
        if (strstr($entry, "name =")) {
            $name = trim(str_replace('"', "", explode("=", $entry)[1]));
            echo "Processor: setting name to {$name}\n";
            $chemical->setName($name);
        }
        if (strstr($entry, "required_reagents")) {
            echo "Processor: processing parents\n";

            $parents = explode(",", trim(str_replace(['required_reagents = list(', ')'], "", $entry)));
            foreach ($parents as $parent) {
                $parentName = explode("=", str_replace(['"'], '', trim($parent)));
                echo "Processor: parent {$parentName[0]}\n";
                $parent = $em->getRepository(Chemical::class)->findOneBy(['idName' => trim($parentName[0])]);
                if ($parent == null) {
                    echo "Processor: parent not found, creating new\n";
                    $idname = trim($parentName[0]);
                    var_dump($idname);
                    $parent = new Chemical();
                    $parent->setIdName('ERROR');
                    $parent->setIdName($idname);
                    $parent->setName($idname);
                    $em->persist($parent);
                    $em->flush($parent);
                }
                echo "Processor: adding parent amount {$parentName[1]}\n";
                $em->persist($chemical);
                $em->persist($parent);

                $chemical->addParent($parent, intval(trim($parentName[1])));
            }
            echo "Processor: done processing parents\n";

        }
        if (strstr($entry, "result_amount")) {
            $produced = intval(trim(str_replace('"', "", explode("=", $entry)[1])));
            echo "Processor: set produced to {$produced}\n";
            $chemical->setProduced($produced);
        }
        if (strstr($entry, "required_temperature = T0C + ")) {
            $temperature = intval(trim(str_replace(['"', 'T0C + '], "", explode("=", $entry)[1]))) + 274;
            echo "Processor: set RequireHeat to {$temperature}\n";
            $chemical->setRequireHeat($temperature);
        }
    }
    echo "Processor: done processing entries\n\n";

    $em->flush($chemical);
}
echo "Processor: done\n";
