<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\ExerciseRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserFixture extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin';
    public const USER_REFERENCE = 'user';
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;
    /**
     * @var ExerciseRepository
     */
    protected $exerciseRepository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ExerciseRepository $exerciseRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->exerciseRepository = $exerciseRepository;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('rene.backhaus@posteo.de')
            ->setRoles(['ROLE_ADMIN']);
        $admin->setPassword( $this->getEncryptedPassword('hdnet', $admin));

        $user = (new User())
            ->setEmail('rene.roboter@posteo.de')
            ->setRoles(['ROLE_USER']);
        $user->setPassword( $this->getEncryptedPassword('hdnet', $user));

        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();

        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
        $this->addReference(self::USER_REFERENCE, $user);
    }

    protected function getEncryptedPassword(string $password, User $user): string
    {
        return $this->passwordEncoder->encodePassword(
            $user,
            $password
        );
    }
}