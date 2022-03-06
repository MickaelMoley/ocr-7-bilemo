<?php 


namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/list",name="api_product_collection_get", format="json", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of Bilemo products",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @OA\Tag(name="Products")
     * @Security(name="Bearer")
     * @return JsonResponse
     */
    public function collection(ProductRepository $productRepository, SerializerInterface $serializer) : JsonResponse
    {


        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), 'json'),
            Response::HTTP_OK,
            [],
            true
        );  
       
    }

    /**
     * Permet de récupérer les informations d'un produit 
     * @Route("/show/{id}", name="api_product_item_get", format="json", methods={"GET"})
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