<?php
/**
 * Picture service.
 */

namespace App\Service;

use App\Entity\Picture;
use App\Entity\Recipe;
use App\Repository\PictureRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PictureService.
 */
class PictureService implements PictureServiceInterface
{
    /**
     * Constructor.
     *
     * @param string $targetDirectory Target directory
     * @param PictureRepository $pictureRepository Picture repository
     * @param PictureUploadServiceInterface $pictureUploadService Picture upload service
     * @param Filesystem $filesystem Filesystem component
     */
    public function __construct(
        readonly string $targetDirectory,
        private readonly PictureRepository $pictureRepository,
        private readonly PictureUploadServiceInterface $pictureUploadService,
        private readonly Filesystem $filesystem
    ) {
    }

    /**
     * Update picture.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Picture      $picture      Picture entity
     * @param Recipe       $recipe      Recipe entity
     * @throws ORMException
     */
    public function update(UploadedFile $uploadedFile, Picture $picture, Recipe $recipe): void
    {
        $filename = $picture->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );

            $this->create($uploadedFile, $picture, $recipe);
        }
    }

    /**
     * Create picture.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Picture      $picture      Picture entity
     * @param Recipe       $recipe      Recipe entity
     * @throws ORMException
     */
    public function create(UploadedFile $uploadedFile, Picture $picture, Recipe $recipe): void
    {
        $pictureFilename = $this->pictureUploadService->upload($uploadedFile);

        $picture->setRecipe($recipe);
        $picture->setFilename($pictureFilename);
        $this->pictureRepository->save($picture);
    }
}
