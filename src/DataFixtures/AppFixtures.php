<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\API\APIUser;
use App\Entity\API\APICustomer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->passwordHasher = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager)
    {
        
        
        //Creer des utilisateurs
        for($i = 0; $i < 10; $i++)
        {
            $user = new APIUser();
            $user->setName("User_" . $i);
            $user->setUsername($user->getName());
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setEnabled(true);
            $user->setRoles([]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user_'.$i));

            //Creer des utilisateurs
            for($j = 0; $j < mt_rand(10,25); $j++)
            {
                $customer = new APICustomer();
                $customer->setFirstname("Customer firstname" . $j);
                $customer->setLastname("Customer lastname".$j);
                $customer->setCreatedAt(new DateTimeImmutable());
                $customer->setAddress(mt_rand(0, 100). "rue du Bois");
                $customer->setCivility('M.');
                $customer->setPhone("070707".mt_rand(0,99).mt_rand(0,99));
                $customer->setApiUser($user);
                
                $manager->persist($customer);
            }
            
            $manager->persist($user);
        }

        //Créer des produits
        for($i = 0; $i < mt_rand(10,100); $i++)
        {
            $product = new Product();
            $product->setBrand('Brand_'.$i);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget efficitur erat. Proin erat risus, aliquam et orci imperdiet, interdum porttitor dolor. Sed vehicula viverra lectus at ullamcorper. Vivamus et vehicula quam. Suspendisse nulla nibh, commodo a sem non, ornare iaculis dui. Nam vel auctor erat. Integer gravida mauris ac risus blandit, a blandit nulla rutrum. Pellentesque pharetra, sem vel malesuada iaculis, dui risus placerat lacus, at eleifend est sem in enim. ');
            $product->setModel('Model_'.$i);
            $product->setPrice(mt_rand(50, 1000));
            $product->setQuantity(mt_rand(0,100));
            $product->setCreatedAt(new DateTimeImmutable());
            $product->setUpdatedAt(new DateTimeImmutable());

            $manager->persist($product);
        }
        
        $manager->flush();
    }
}
