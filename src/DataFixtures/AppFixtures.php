<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\BlogContent;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        $author1 = new User();
        $hash = $this->encoder->encodePassword($author1,'password');
        $author1->setEmail($faker->email)
                ->setFirstName('mario')
                ->setLastName($faker->lastName)
                ->setPicture($faker->imageUrl($width = 640, $height = 480))
                ->setHash($hash)
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->text)
                ->setText($faker->text)
                ->setSlug($faker->slug);
        $author2 = clone $author1;
        $author2->setFirstName('luigi');
        $author2->setEmail($faker->email);
        $author = [$author1,$author2];

        $manager->persist($author1);
        $manager->persist($author2);

        for($i=0; $i<30;$i++){
            $blog = new BlogContent();
            $blog   ->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true))
                    ->setBody($faker->text)
                    ->setAuthor($author[rand(0,1)]);
            $manager->persist($blog);
        }
        $manager->flush();
    }
}
