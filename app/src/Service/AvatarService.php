<?php
/**
 * Avatar service.
 */

namespace App\Service;

use App\Entity\Avatar;
use App\Repository\AvatarRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AvatarService.
 */
class AvatarService implements AvatarServiceInterface
{
    /**
     * Constructor.
     *
     * @param string                     $targetDirectory   Target directory
     * @param AvatarRepository           $avatarRepository  Avatar repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     * @param Filesystem                 $filesystem        Filesystem component
     */
    public function __construct(
        private readonly string $targetDirectory,
        private readonly AvatarRepository $avatarRepository,
        private readonly FileUploadServiceInterface $fileUploadService,
        private readonly Filesystem $filesystem
    ) {
    }

    /**
     * Update avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param UserInterface $user         User entity
     *
     * @throws ORMException
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );

            $this->create($uploadedFile, $avatar, $user);
        }
    }

    /**
     * Create avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param UserInterface $user         User entity
     *
     * @throws ORMException
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $avatarFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setUser($user);
        $avatar->setFilename($avatarFilename);
        $this->avatarRepository->save($avatar);
    }
}
