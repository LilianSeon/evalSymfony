<?php

namespace App\EventSubscriber\Entity;

use App\Entity\Oeuvre;
use App\Services\FileService;
use App\Services\StringService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OeuvreSubscriber implements EventSubscriber
{
    /*
     * Injecter un service dans un autre service hors contrôleur
     *      - créer une propriété de classe
     *      - créer un constructeur qui reçoit en paramètre les services à injecter
     *      - dans le constructeur, lier le paramètre à la propriété de classe
     */
    private $stringService;
    private $fileService;

    public function __construct(StringService $stringService, FileService $fileService)
    {
        $this->stringService = $stringService;
        $this->fileService = $fileService;
    }


    /*
     * souscripteur d'entité doctrine
     *  - la méthode doit retourner un tableau des évenements à écouter
     *      - prePersist / postPersist : avant ou après une insertion
     *      - preUpdate /postUpdate : avant ou après une modification
     *      - preRemove / postRemove : avant ou après une suppression
     *      - postLoad : après l'instanciation d'une entité
     *  - les méthodes liées aux évenements doivent reprendre strictement le nom de l'évenement à écouter
     *  - toutes les méthodes reçoivent un paramètre de type LifecycleEventArgs
     *  - référencer le souscripteur dans config/services.yaml
    */

    public function prePersist(LifecycleEventArgs $args):void
    {
        // par défaut, les souscripteur écoutent toutes les entités
        $entity = $args->getObject();

        // si l'entité n'est pas Product
        if (!$entity instanceof Oeuvre){
            return;
        }else {

            // Transfert d'image
            if($entity->getImage() instanceof UploadedFile) {
                $this->fileService->upload($entity->getImage(), 'img/oeuvre');

                //mise à jour de la propriété image
                $entity->setImage($this->fileService->getFileName());
            }
        }
    }

    public function postLoad(LifecycleEventArgs $args):void
    {
        // par défaut, les souscripteur écoutent toutes les entités
        $entity = $args->getObject();

        // si l'entité n'est pas Product
        if (!$entity instanceof Oeuvre){
            return;
        }else {
            //création d'une propriété dynamique pour stocker le nom de l'image
            $entity->prevImage = $entity->getImage();
        }
    }

    public function preUpdate(LifecycleEventArgs $args):void
    {
        // par défaut, les souscripteur écoutent toutes les entités
        $entity = $args->getObject();

        // si l'entité n'est pas Product
        if (!$entity instanceof Oeuvre){
            return;
        }else {
            // si une image a été sélectionnée
            if ($entity->getImage() instanceof UploadedFile){
                //transfert de la nouvelle image
                $this->fileService->upload($entity->getImage(), 'img/oeuvre');
                $entity->setImage($this->fileService->getFileName());

                // supprimer l'ancienne image
                if(file_exists("img/oeuvre/{$entity->prevImage}")) {
                    $this->fileService->remove('img/oeuvre', $entity->prevImage);
                }
            }
            // si aucune image n'a été sélectionnée
            else{
                $entity->setImage($entity->prevImage);
            }
        }
        //dd('update')
    }

    public function getSubscribedEvents():array
    {
        return [
            Events::prePersist,
            Events::postLoad,
            Events::preUpdate,
        ];
    }
}