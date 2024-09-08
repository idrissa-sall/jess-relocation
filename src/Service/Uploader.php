<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ){}

    // uploader
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }


    // remove uploaded file
    public function remove(string $fileName): void
    {
        // crée l'instance FileSystem
        $fileSystem = new Filesystem();
        // supprime le fichier du repertoire
        $fileSystem->remove($this->getTargetDirectory() . '/' . $fileName);
    }


    // resize image
    public function resize(string $fileName, int $width, int $height): void
    {
        $imagine = new Imagine();
        $photo = $imagine->open($fileName);
        $photo->resize(new Box($width, $height))->save($fileName);
    }


    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

}