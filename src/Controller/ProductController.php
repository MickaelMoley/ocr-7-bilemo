<?php 


namespace App\Controller;

use App\Entity\Product;
use App\Representation\Product as Products;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

/**
 * @Route("/products")
 */
class ProductController
{

    /**
     * Permet de récupérer la liste de tous les produits
     * @Route(name="api_product_collection_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of Bilemo products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class))
     *     )
     * )
	 *  @OA\Parameter(name="page",
	 *    in="query", description="Specify the page you want to browse",
	 *    @OA\Schema(type="int")
	 *  ),
	 *  @OA\Parameter(name="order",
	 *    in="query", description="Specify sort order page 'asc' or 'desc'",
	 *    @OA\Schema(type="string")
	 *  ),
	 *  @OA\Parameter(name="limit",
	 *    in="query", description="Specify the number of items you want to display per page",
	 *    @OA\Schema(type="int")
	 *  )
	 * @OA\Response(
	 *     response = 404,
	 *     description = "Page Not Found"
	 * )
     * @OA\Tag(name="Products")
     * @Security(name="Bearer")
     * @return JsonResponse
     */
    public function collection(
		ProductRepository $productRepository,
		SerializerInterface $serializer,
		Request $request, UrlGeneratorInterface $urlGenerator) : JsonResponse
    {
		$page = $request->query->get('page', 1);
		$order = $request->query->get('order', 'asc');
		$limit = $request->query->get('limit', 5);

		$collection = $productRepository->getListProducts(
			(int) $page,
			(int) $limit,
			$order);


		$response =  new JsonResponse(
			$serializer->serialize(
				new Products($collection, $urlGenerator),
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
     * Permet de récupérer les informations d'un produit 
     * @Route("/{id}", name="api_product_item_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns an Bilemo product",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @OA\Tag(name="Products")
     * @Security(name="Bearer")
     * 
     */
    public function item(Product $product, SerializerInterface $serializer)
    {   
     
        return new JsonResponse(
            $serializer->serialize($product, 'json'),
            Response::HTTP_OK,
            [],
            true
        ); 
        
    }
}