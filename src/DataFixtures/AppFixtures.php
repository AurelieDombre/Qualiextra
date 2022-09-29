<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\AddressProvider;
use App\DataFixtures\Provider\PictureProvider;
use App\Entity\Book;
use App\Entity\Establishment;
use App\Entity\Gallery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Package;
use App\Entity\Style;
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Tag;
use App\Services\Geocodage;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\DBAL\Connection;

class AppFixtures extends Fixture
{
    private $connection;
    private $passwordHasher;
    private $geocodage;

    /**
     *
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(Connection $connection, UserPasswordHasherInterface $hasher, Geocodage $geocodage)
    {
        $this->connection = $connection;
        $this->passwordHasher = $hasher;
        $this->geocodage = $geocodage;
    }

    private function truncate()
    {
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        $this->connection->executeQuery('TRUNCATE TABLE book');
        $this->connection->executeQuery('TRUNCATE TABLE establishment');
        $this->connection->executeQuery('TRUNCATE TABLE package');
        $this->connection->executeQuery('TRUNCATE TABLE package_type');
        $this->connection->executeQuery('TRUNCATE TABLE tag');
        $this->connection->executeQuery('TRUNCATE TABLE tag_establishment');
        $this->connection->executeQuery('TRUNCATE TABLE type');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        $this->connection->executeQuery('SET foreign_key_checks = 1');
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();
        $addressProvider = new AddressProvider;
        $pictureProvider = new PictureProvider;

        $faker = Faker\Factory::create('fr_FR');

        $typesListEntity = [];
        $typesList = ["Expérience hôtelière", "Dégustation de spiritueux", "Diner", "Expérience atypique"];


        foreach ($typesList as $typeName) {
            $type = new Type;

            $type->setName($typeName);
            $typesListEntity[] = $type;

            $manager->persist($type);
        }

        //Users
        $usersList = [];
        // ------------userUser-----------
        $user = new User;
        $user->setFirstname("Sébastien");
        $user->setLastname($faker->lastname());
        $user->setEmail("user@user.com");
        $plaintextPassword = "user";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $usersList[] = $user;
        $manager->persist($user);

        //2eme user
        $user2 = new User;
        $user2->setFirstname("Vanessa");
        $user2->setLastname($faker->lastname());
        $user2->setEmail("vanessa@user.com");
        $plaintextPassword = "user";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user2,
            $plaintextPassword
        );
        $user2->setPassword($hashedPassword);
        $user2->setRoles(['ROLE_USER']);
        $usersList[] = $user2;
        $manager->persist($user2);

        // ------------userAdmin-----------

        $userAdmin = new User();
        $userAdmin->setFirstname($faker->firstname());
        $userAdmin->setLastname($faker->lastname());
        $userAdmin->setEmail("admin@admin.com");
        $plaintextPassword = "admin";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userAdmin,
            $plaintextPassword
        );
        $userAdmin->setPassword($hashedPassword);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $usersList[] = $userAdmin;
        $manager->persist($userAdmin);

        // ------------userPro-----------

        $userPro = new User();
        $userPro->setFirstname("Laura");
        $userPro->setLastname($faker->lastname());
        $userPro->setEmail("pro@pro.com");
        $plaintextPassword = "pro";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userPro,
            $plaintextPassword
        );
        $userPro->setPassword($hashedPassword);
        $userPro->setRoles(['ROLE_PRO']);
        $usersList[] = $userPro;
        $manager->persist($userPro);


        //2em pro
        $userPro= new User();
        $userPro->setFirstname("Aurélie");
        $userPro->setLastname($faker->lastname());
        $userPro->setEmail("aurelie@pro.com");
        $plaintextPassword = "pro2";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userPro,
            $plaintextPassword
        );
        $userPro->setPassword($hashedPassword);
        $userPro->setRoles(['ROLE_PRO']);
        $usersList[] = $userPro;
        $manager->persist($userPro);

        //3em pro (Coup de coeur Qualiextra)
        $userPro= new User();
        $userPro->setFirstname('Qualiextra');
        $userPro->setLastname('1 Coup de coeur');
        $userPro->setEmail("qualiextra@pro.com");
        $plaintextPassword = "qualiextra";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $userPro,
            $plaintextPassword
        );
        $userPro->setPassword($hashedPassword);
        $userPro->setRoles(['ROLE_PRO']);
        $usersList[] = $userPro;
        $manager->persist($userPro);


        //Styles
        $stylesList = [];
        //--------japanese style-------
        $stylejapanese = new Style();
        $stylejapanese->setName('japonnais');
        $stylesList[] = $stylejapanese;
        $manager->persist($stylejapanese);
        //--------french style-------
        $stylefrench = new Style();
        $stylefrench->setName('français');
        $stylesList[] = $stylefrench;
        $manager->persist($stylefrench);
        //--------italian style-------
        $styleitalian = new Style();
        $styleitalian->setName('italien');
        $stylesList[] = $styleitalian;
        $manager->persist($styleitalian);
        //--------indian style-------
        $styleindian = new Style();
        $styleindian->setName('indien');
        $stylesList[] = $styleindian;
        $manager->persist($styleindian);
        //--------vietnamian style-------
        $stylevietnamian = new Style();
        $stylevietnamian->setName('vietnamien');
        $stylesList[] = $stylevietnamian;
        $manager->persist($stylevietnamian);


        //Establishments
        $establishmentsList = [];

        for ($j = 1; $j < 30; $j++) {
            $establishment = new Establishment();
            $establishment->setName($faker->name());
            $establishment->setAddress($addressProvider->getRandomAddress() . ' 75000 PARIS');
            $establishment->setPicture('https://picsum.photos/id/'.mt_rand(1, 100).'/450/300');
            $establishment->setPhone($faker->phoneNumber());
            $establishment->setEmail($faker->email());
            $establishment->setPrice(rand(5, 100));
            $establishment->setWebsite($faker->url());
            $style = $stylesList[mt_rand(0, count($stylesList) - 1)];
            $establishment->setStyle($style);
            $establishment->setDescription($faker->realText(100));
            $establishment->setUser($usersList[rand(3,4)]);
            $establishmentAddress = $establishment->getAddress();
            $coordinates = $this->geocodage->geocoding($establishmentAddress);
            $lat = $coordinates['lat'];
            $long = $coordinates['lng'];

            $establishment->setLatitudes($lat);
            $establishment->setLongitudes($long);

            $establishmentsList[] = $establishment;

            $manager->persist($establishment);
        }

        //Packages
        $packagesList = [];

        for ($i = 1; $i < 30; $i++) {
            $package = new Package();

            $package->setName($faker->name());
            //$package->setPicture('https://picsum.photos/id/' . mt_rand(1, 100) . '/450/300');
            $package->setPrice(rand(5, 100));
            $package->setDescription($faker->realText(100));
            $package->setExpireOn($faker->dateTimeBetween('-1 week', '+4 week'));
            $package->setEstablishment($establishmentsList[mt_rand(0, count($establishmentsList) - 1)]);

            $n = mt_rand(1, 4);

            for ($z = 1; $z <= $n; $z++) {
                $package->addType($typesListEntity[mt_rand(0, count($typesListEntity) - 1)]);
            }

            $packagesList[] = $package;

            $manager->persist($package);
        }

        // Book
        $booksList = [];

        for ($m = 1; $m < 101; $m++) {
            $book = new Book();
            $book->setUser($usersList[rand(0,1)]);
            $book->setStatus(rand(0, 2));
            $book->setPrice(rand(5, 100));
            $package = $packagesList[mt_rand(0, count($packagesList) - 1)];
            $book->setPackages($package);
            $booksList[] = $book;
            $manager->persist($book);
        }


        //# Tag

        $tagList = [];
        //--------Piscine-------
        $tagpiscine = new Tag();
        $tagpiscine->setName('piscine');
        $tagList[] = $tagpiscine;
        $manager->persist($tagpiscine);
        //--------Rooftop-------
        $tagrooftop = new Tag();
        $tagrooftop->setName('rooftop');
        $tagList[] = $tagrooftop;
        $manager->persist($tagrooftop);
        //--------Terrasse-------
        $tagterrasse = new Tag();
        $tagterrasse->setName('terrasse');
        $tagList[] = $tagterrasse;
        $manager->persist($tagterrasse);
        //--------Piano-------
        $tagpiano = new Tag();
        $tagpiano->setName('piano');
        $tagList[] = $tagpiano;
        $manager->persist($tagpiano);


        foreach ($establishmentsList as $key => $establishment) {
            $nbMaxtags = mt_rand(1, 3);
            for ($n = 1; $n <= $nbMaxtags; $n++) {
                $establishment->addTag($tagList[mt_rand(0, count($tagList) - 1)]);
                $manager->persist($establishment);
            }
        }

        //# Gallery
        foreach ($packagesList as $key => $package) {
            $nbMaxpicture = mt_rand(2, 4);
            for ($n = 1; $n <= $nbMaxpicture; $n++) {
                $gallery = new Gallery;
                $gallery->setPicture($pictureProvider->getRandomPicture());
                $gallery->setPackage($package);
                $manager->persist($gallery);
            }
        }
        
        $manager->flush();
    }
}
