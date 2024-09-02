<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel.
 *
 * This is the main kernel class for the Symfony application.
 * It uses MicroKernelTrait to configure routes and services directly in the kernel.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
