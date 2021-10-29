<?php 


namespace App\Controller;

use DateTimeImmutable;
use App\Entity\API\APIUser;
use Hateoas\HateoasBuilder;
use OpenApi\Annotations as OA;
use App\Entity\API\APICustomer;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\API\APICustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Hateoas\UrlGenerator\CallableUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Security as OASecurity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @Route("/users")
 */
class UserController
{
    private $security; 

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Permet de récupérer la liste des utilisateurs d'un client
     * @Route("/{id}/customers/",name="api_user_customers_collection_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customer of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     * @return JsonResponse
     */
    public function collectionCustomer(
        APIUser $aPIUser,
        SerializerInterface $serializer, UrlGeneratorInterface $urlGeneratorInterface) : JsonResponse
    {
        //On autorise l'utilisateur 
        if($aPIUser == $this->security->getUser())
        {
            //On lui passe l'url pour pouvoir générer les liens pour notre 
            $builder = $this->getBuilder($urlGeneratorInterface);
                

            $response =  new JsonResponse(
                $builder->serialize($aPIUser->getCustomers(), 'json'),
                Response::HTTP_OK,
                [],
                true
            );

            $response->setPublic();
            $response->setMaxAge(3600);

            return $response;

        }
        //On envoie un message d'erreur car l'utilisateur ne l'API n'est pas le même utilisateur que l'utilisateur authentifié
        return new JsonResponse([
            'type'      => "forbidden",
            'message'   => "You are not allowed to access to this ressource."
        ]);


          
       
    }

    /**
     * Permet de récupérer les informations d'un client
     * @Route("/{id}/customers/{idCustomer}", name="api_user_customers_item_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customer of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     */
    public function itemCustomer(APIUser $user, APICustomerRepository $customerRepository, SerializerInterface $serializer, Request $request,
    UrlGeneratorInterface $urlGeneratorInterface)
    {   

        $customerId = $request->attributes->get('idCustomer');

        $customer = $customerRepository->findOneBy(['id' => $customerId, 'apiUser' => $user]);

        //On autorise l'utilisateur 
        if($user == $this->security->getUser())
        {

            if($customer)
            {

            //On lui passe l'url pour pouvoir générer les liens pour notre 
            $builder = $this->getBuilder($urlGeneratorInterface);

                $response = new JsonResponse(
                   $builder->serialize($customer, 'json'),
                    Response::HTTP_OK,
                    [],
                    true
                ); 
    
                $response->setPublic();
                $response->setMaxAge(3600);
    
                return $response;
            }

            return new JsonResponse(
                ['message' => 'Customer not exist.'],
                Response::HTTP_OK,
                [],
                false
            ); 

           

        }
        //On envoie un message d'erreur car l'utilisateur ne l'API n'est pas le même utilisateur que l'utilisateur authentifié
        return new JsonResponse([
            'type'      => "forbidden",
            'message'   => "You are not allowed to access to this ressource."
        ]);
     
        
        
    }

    /**
     * Permet de créer un client pour un utilisateur
     * @Route("/{id}/customers", name="api_user_customers_post", format="json", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customer of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     * @return void
     */
    public function postCustomer(APIUser $user, 
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGeneratorInterface)
    {
        

         //On autorise l'utilisateur 
         if($user == $this->security->getUser())
         {
 
            $post = $serializer->deserialize($request->getContent(), APICustomer::class, 'json');
            $post->setCreatedAt(new DateTimeImmutable());
            $post->setApiUser($this->security->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            
            //On lui passe l'url pour pouvoir générer les liens pour notre 
            $builder = $this->getBuilder($urlGeneratorInterface);


            return new JsonResponse(
              $builder->serialize($post, 'json')
                , Response::HTTP_CREATED, 
                [],
                 true
            );
 
            
 
         }
         //On envoie un message d'erreur car l'utilisateur ne l'API n'est pas le même utilisateur que l'utilisateur authentifié
         return new JsonResponse([
             'type'      => "forbidden",
             'message'   => "You are not allowed to access to this ressource."
         ]);
    }

    /**
     * Permet de supprimer un client d'un utilisateur
     * @Route("/{id}/customers/{idCustomer}", name="api_user_customers_item_delete", format="json", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customer of an user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     */
    public function itemDeleteCustomer(APIUser $user, SerializerInterface $serializer, EntityManagerInterface $entityManager,
        Request $request)
    {   

        $customerId = $request->attributes->get('idCustomer');

        $customer = $entityManager->getRepository(APICustomer::class)->findOneBy(['id' => $customerId, 'apiUser' => $user]);



          //On autorise l'utilisateur 
          if($user == $this->security->getUser())
          {
  
            if($customer)
            {
                $entityManager->remove($customer);
                $entityManager->flush();
    
                return new JsonResponse(
                    ['message' => 'User deleted.'],
                    Response::HTTP_OK,
                    [],
                    false
                );
            }
    
            return new JsonResponse(
                ['message' => 'Customer unknown.'],
                Response::HTTP_OK,
                [],
                false
            );
             
  
          }
          //On envoie un message d'erreur car l'utilisateur ne l'API n'est pas le même utilisateur que l'utilisateur authentifié
          return new JsonResponse([
              'type'      => "forbidden",
              'message'   => "You are not allowed to access to this ressource."
          ]);
     
         
        
    }


    /**
     * Fonction permettant de construire la fonction permettant de générer les liens découvrable pour API
     *
     * @param UrlGeneratorInterface $urlGeneratorInterface
     */
    private function getBuilder(UrlGeneratorInterface $urlGeneratorInterface)
    {
        return HateoasBuilder::create()
        ->setUrlGenerator(
            null,
            new CallableUrlGenerator(function ($route, array $parameters, $absolute) use ($urlGeneratorInterface) {
                return $urlGeneratorInterface->generate($route, $parameters, $absolute);
            })
        )
        ->build();
    }
}