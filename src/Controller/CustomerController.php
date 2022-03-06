<?php 


namespace App\Controller;

use App\Http\ApiResponse;
use App\Representation\Customer;
use DateTimeImmutable;
use App\Entity\API\APIUser;
use Doctrine\ORM\QueryBuilder;
use Hateoas\HateoasBuilder;
use Hateoas\Representation\Factory\PagerfantaFactory;
use JMS\Serializer\Exception\ValidationFailedException;
use JMS\Serializer\SerializationContext;
use OpenApi\Annotations as OA;
use App\Entity\API\APICustomer;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Repository\API\APICustomerRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/customers")
 */
class CustomerController
{
    private $security; 

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Permet de récupérer la liste des utilisateurs d'un client
     * @Route("/",name="api_customers_collection_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
	 *  @OA\Parameter(name="page",
	 *    in="query",
	 *    @OA\Schema(type="int")
	 *  ),
	 *  @OA\Parameter(name="order",
	 *    in="query",
	 *    @OA\Schema(type="string")
	 *  ),
	 *  @OA\Parameter(name="limit",
	 *    in="query",
	 *    @OA\Schema(type="int")
	 *  )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     * @return JsonResponse
     */
    public function collectionCustomer(APICustomerRepository $APICustomerRepository,
        SerializerInterface $serializer, UrlGeneratorInterface $urlGeneratorInterface, Request $request) : JsonResponse
    {
		$page = $request->query->get('page', 1);
		$order = $request->query->get('order', 'asc');
		$limit = $request->query->get('limit', 5);

		//On lui passe l'url pour pouvoir générer les liens pour notre
		$builder = $this->getBuilder($urlGeneratorInterface);

		$context = new SerializationContext();
		$context->setGroups('get');

		$collection = $APICustomerRepository->getListCustomers(
			$this->security->getUser(),
			(int) $page,
			(int) $limit,
			$order);


		$response =  new JsonResponse(
			$builder->serialize(
				new Customer($collection, $urlGeneratorInterface),
				'json'),
			Response::HTTP_OK,
			[],
			true
		);

		$response->setPublic();
		$response->setMaxAge(3600);

		return $response;


          
       
    }

    /**
     * Permet de récupérer les informations d'un client
     * @Route("/{id}", name="api_customers_item_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns an customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     */
    public function itemCustomer(APICustomer $APICustomer, APICustomerRepository $customerRepository, SerializerInterface $serializer, Request $request,
    UrlGeneratorInterface $urlGeneratorInterface)
    {

		//On lui passe l'url pour pouvoir générer les liens pour notre
		$builder = $this->getBuilder($urlGeneratorInterface);

		$response = new JsonResponse(
			$builder->serialize($APICustomer, 'json'),
			Response::HTTP_OK,
			[],
			true
		);

		$response->setPublic();
		$response->setMaxAge(3600);

		return $response;



    }

    /**
     * Permet de créer un client pour un utilisateur
     * @Route("", name="api_customers_post", format="json", methods={"POST"})
     * @OA\Response(
     *     response=200,
     *     description="Create an customer",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=APICustomer::class))
     *     )
     * )
     * @OA\Tag(name="Customers")
     * @OASecurity(name="Bearer")
     * @return JsonResponse
	 */
    public function postCustomer(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGeneratorInterface,
		ValidatorInterface $validator)
    {


		$post = $serializer->deserialize($request->getContent(), APICustomer::class, 'json');
		$post->setCreatedAt(new DateTimeImmutable());
		$post->setApiUser($this->security->getUser());

		$errors = $validator->validate($post);

		//Si erreur de validation alors on lance une exception
		if(count($errors))
		{
			throw new ValidationFailedException($errors);
		}


		$entityManager->persist($post);
		$entityManager->flush();

		//On lui passe l'url pour pouvoir générer les liens pour notre
		$builder = $this->getBuilder($urlGeneratorInterface);

		$context = new SerializationContext();
		$context->setGroups('get');

		return new JsonResponse(
			$builder->serialize($post, 'json', $context)
			, Response::HTTP_CREATED,
			[],
			true
		);
    }

	/**
	 * Permet de supprimer un client d'un utilisateur
	 * @Route("/{id}", name="api_customers_item_delete", format="json", methods={"DELETE"})
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
	public function itemDeleteCustomer(
		APICustomer $customer,
		SerializerInterface $serializer,
		EntityManagerInterface $entityManager,
		Request $request)
	{

		$entityManager->remove($customer);
		$entityManager->flush();

		return new JsonResponse(
			null,
			Response::HTTP_NO_CONTENT,
			[],
			false
		);


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