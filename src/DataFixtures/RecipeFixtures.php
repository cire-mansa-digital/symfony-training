<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Category;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly SluggerInterface $slugger) {

    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \FakerRestaurant\Provider\fr_FR\Restaurant($faker) );
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $categories = ['Plat Chaud', 'Plat Principal', 'Dîner', 'Goûter','Petit Dejeûner'];
       foreach ($categories as $c) {
         $category = new Category() ;
         $category->setName($c)
         ->setSlug($this->slugger->slug(strtolower($c)))
        ->setDescription($faker->paragraphs(3, true))
        ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
        ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
         ;

         $this->addReference($c, $category);

         $manager->persist($category);
       }


        for ($i = 0; $i < 10; $i++) {
            $recipe = new Recipe() ;
            $title = $faker->foodName;
            $recipe->setTitle($title)
            ->setSlug($this->slugger->slug(strtolower($title)))
            ->setDuration($faker->numberBetween(5,60))
            ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime))
            ->setUpdateAt(DateTimeImmutable::createFromMutable($faker->dateTime))
            ->setContent($faker->paragraphs(5,true))
            ->setImage($faker->imageUrl($width = 640, $height = 480))
            ->setCategory($this->getReference($faker->randomElement($categories),Category::class))
            ->setRuser($this->getReference('User'. $faker->numberBetween(1,10),User::class))
            ;

            $manager->persist($recipe);
        }


        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return  [
            UserFixtures::class,
        ];
    }
}
