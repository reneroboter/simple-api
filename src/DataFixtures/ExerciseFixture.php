<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use App\Entity\User;
use DateTime;
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
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getExercises() as $raw) {
            $exercise = new Exercise();
            $exercise->setName($raw['name']);
            $exercise->setDate(new DateTime($raw['date']));
            $exercise->setReps($raw['reps']);
            $exercise->setUser($this->getReference($raw['user']));
            $manager->persist($exercise);
        }
        $manager->flush();
    }

    private function getExercises()
    {
        return [
            [
                'name' => 'No pain, no gain',
                'date' => '01.08.2019',
                'reps' => '3-4',
                'user' => UserFixture::USER_REFERENCE,
            ],
            [
                'name' => 'Listen. Look. Learn',
                'date' => '02.08.2019',
                'reps' => '3-4',
                'user' => UserFixture::ADMIN_USER_REFERENCE,
            ], [
                'name' => 'What you say is what you are',
                'date' => '03.08.2019',
                'reps' => '4-5',
                'user' => UserFixture::USER_REFERENCE,
            ],
            [
                'name' => 'Listen hard. Speak soft',
                'date' => '04.08.2019',
                'reps' => '1-2',
                'user' => UserFixture::USER_REFERENCE,
            ], [
                'name' => 'Know your mode make-up',
                'date' => '05.08.2019',
                'reps' => '3-4',
                'user' => UserFixture::ADMIN_USER_REFERENCE,
            ],
            [
                'name' => 'No pain, no gain',
                'date' => '06.08.2019',
                'reps' => '2-3',
                'user' => UserFixture::USER_REFERENCE,
            ], [
                'name' => 'You become what you say',
                'date' => '07.08.2019',
                'reps' => '3-4',
                'user' => UserFixture::ADMIN_USER_REFERENCE,
            ],
            [
                'name' => 'Training is useless without a purpose',
                'date' => '08.08.2019',
                'reps' => '3-4',
                'user' => UserFixture::USER_REFERENCE,
            ],
        ];
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            UserFixture::class
        ];
    }
}
