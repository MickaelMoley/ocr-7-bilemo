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

/**
 * @Route("/products")
 */
class ProductController
{

    /**
     * Permet de récupérer la liste de tous les produits
     * @Route(name="api_product_collection_get", format="json", methods={"GET"})
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
     * @Route("/{id}", name="api_product_item_get", format="json", methods={"GET"})
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