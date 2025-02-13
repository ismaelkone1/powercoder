<?php

namespace App\DataFixtures;

use App\Entity\Besoin;
use App\Entity\Competence;
use App\Entity\Salarie;
use App\Entity\SalarieCompetence;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setNom($faker->lastName);
            $manager->persist($user);
            $users[] = $user;
        }

        $salaries = [];
        for ($i = 0; $i < 10; $i++) {
            $salarie = new Salarie();
            $salarie->setNom($faker->lastName);
            $manager->persist($salarie);
            $salaries[] = $salarie;
        }

        $competences = [];
        $listElements = ['BR', 'JD', 'MN', 'IF', 'AD'];
        for ($i = 0; $i < count($listElements); $i++) {
            $competence = new Competence();
            $competence->setType($listElements[$i]);
            $manager->persist($competence);
            $competences[] = $competence;
        }

        for ($i = 0; $i < 10; $i++) {
            $besoin = new Besoin();
            $besoin->setLibelle($faker->sentence);
            $besoin->setDate($faker->dateTimeThisYear);
            $besoin->setClient($faker->randomElement($users));

            foreach ($faker->randomElements($competences, 3) as $competence) {
                $besoin->addCompetence($competence);
            }

            $manager->persist($besoin);
        }

        foreach ($salaries as $salarie) {
            foreach ($competences as $competence) {
                $salarieCompetence = new SalarieCompetence();
                $salarieCompetence->setSalarie($salarie);
                $salarieCompetence->setCompetence($competence);
                $salarieCompetence->setInteret($faker->numberBetween(1, 10));
                $manager->persist($salarieCompetence);
            }
        }

        $manager->flush();
    }
}