<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ExerciseFixture
 * @package App\DataFixtures
 * @method User getReference(string $name)
 */
class ExerciseFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) : void
    {
        $amount = 3;
        for ($i = 1; $i < $amount; $i++) {
            $exercise = new Exercise();
            $exercise->setName('Exercise ' . $i);
            $exercise->setDate(new \DateTime);
            $exercise->setReps('3-4');
            $exercise->setUser($this->getReference(UserFixture::ADMIN_USER_REFERENCE));
            $manager->persist($exercise);
        }
        $amount = 4;
        for ($i = 1; $i < $amount; $i++) {
            $exercise = new Exercise();
            $exercise->setName('Exercise ' . $i);
            $exercise->setDate(new \DateTime);
            $exercise->setReps('3-4');
            $exercise->setUser($this->getReference(UserFixture::USER_REFERENCE));
            $manager->persist($exercise);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixture::class
        ];
    }
}
