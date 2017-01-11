<?php
// src/Yoda/UserBundle/DataFixtures/ORM/LoadUsers.php
namespace Yoda\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yoda\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsers implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setUsername('darth');
        $user->setEmail('darth@deathstar.com');
        $user->setPassword($this->encodePassword($user, 'darthpass'));
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('wayne');
        $admin->setEmail('wayne@deathstar.com');
        $admin->setPassword($this->encodePassword($admin, 'waynepass'));
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setIsActive(true);
        $manager->persist($admin);

        // the queries aren't done until now
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    private function encodePassword(User $user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }
}