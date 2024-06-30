<?php
/**
* App Fixtures.
 */
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager.
     */
    public function load(ObjectManager $manager): void
    {
        $manager->flush();
    }
}
