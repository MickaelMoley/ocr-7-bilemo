<?php 


namespace App\Controller;

use DateTimeImmutable;
use App\Entity\API\APIUser;
use App\Entity\API\APICustomer;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\API\APICustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
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
     * @return JsonResponse
     */
    public function collectionCustomer(
        APIUser $aPIUser,
        SerializerInterface $serializer) : JsonResponse
    {
        //On autorise l'utilisateur 
        if($aPIUser == $this->security->getUser())
        {
            return new JsonResponse(
                $serializer->serialize($aPIUser->getCustomers(), 'json', ['groups' => 'get']),
                Response::HTTP_OK,
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
     * Permet de récupérer les informations d'un client
     * @Route("/{id}/customers/{idCustomer}", name="api_user_customers_item_get", format="json", methods={"GET"})
     * 
     */
    public function itemCustomer(APIUser $user, APICustomerRepository $customerRepository, SerializerInterface $serializer, Request $request)
    {   

        $customerId = $request->attributes->get('idCustomer');

        $customer = $customerRepository->findOneBy(['id' => $customerId, 'apiUser' => $user]);

        if($customer)
        {
            return new JsonResponse(
                $serializer->serialize($customer, 'json', ['groups' => 'get']),
                Response::HTTP_OK,
                [],
                true
            ); 
        }
        else {
            return new JsonResponse(
                ['message' => 'Customer not exist.'],
                Response::HTTP_OK,
                [],
                false
            ); 
        }

     
        
        
    }

    /**
     * Permet de créer un client pour un utilisateur
     * @Route("/{id}/customers", name="api_user_customers_post", format="json", methods={"POST"})
     * @return void
     */
    public function postCustomer(
        Request $request, 
        SerializerInterface $serializer, 
        EntityManagerInterface $entityManager)
    {
        $post = $serializer->deserialize($request->getContent(), APICustomer::class, 'json');
        $post->setCreatedAt(new DateTimeImmutable());
        $post->setApiUser($this->security->getUser());
        $entityManager->persist($post);
        $entityManager->flush();
        

        return new JsonResponse(
            $serializer->serialize($post, 'json', ['groups' => 'get'])
            , Response::HTTP_CREATED, 
            [],
             true
        );
    }

    /**
     * Permet de supprimer un client d'un utilisateur
     * @Route("/{id}/customers/{idCustomer}", name="api_user_customers_item_delete", format="json", methods={"DELETE"})
     * 
     */
    public function itemDeleteCustomer(APIUser $user, SerializerInterface $serializer, EntityManagerInterface $entityManager,
        Request $request)
    {   

        $customerId = $request->attributes->get('idCustomer');

        $customer = $entityManager->getRepository(APICustomer::class)->findOneBy(['id' => $customerId, 'apiUser' => $user]);

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
}