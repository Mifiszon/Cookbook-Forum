<?php
/**
 * Picture service interface.
 */

namespace App\Service;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Recipe;

/**
 * Interface PictureServiceInterface.
 */
interface PictureServiceInterface
{
    /**
     * Create picture.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Picture      $picture      Picture entity
     * @param Recipe       $recipe       Recipe entity
     */
    public function create(UploadedFile $uploadedFile, Picture $picture, Recipe $recipe): void;

    /**
     * Update avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Picture      $picture      Picture entity
     * @param Recipe       $recipe       Recipe entity
     */
    public function update(UploadedFile $uploadedFile, Picture $picture, Recipe $recipe): void;
}
