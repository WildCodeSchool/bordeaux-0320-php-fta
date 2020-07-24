<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PictureService
{
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function uploadImage($pictureFile)
    {
        $newFilename = uniqid().'.'.$pictureFile->guessExtension();

        try {
            $pictureFile->move(
                $this->container->getParameter('upload_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            //TODO ... handle exception if something happens during file upload
        }

        return $newFilename;
    }

    public function deleteOldPicture($pictureName): bool
    {
        return unlink($this->container->getParameter('upload_directory'). '/' . $pictureName);
    }
}
