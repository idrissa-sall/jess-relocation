<?php

namespace App\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Review;
use Symfony\Component\Filesystem\Filesystem;

class ImageDeleteListener
{

    public function __construct(private string $targetDirectory){}

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // verify if entity is type of Review
        if (!$entity instanceof Review) {
            return;
        }

        $filesystem = new Filesystem();
        $imagePath = $this->targetDirectory.'/'.$entity->getProfilPicture();

        // verify if file exist and remove it
        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
    }
}