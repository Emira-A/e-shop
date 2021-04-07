<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     *Page Home
     * http://localhost:8000/
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(){

        // Recuperation des articles de la BDD
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
        //return new Response('<h1>ACCUEIL</h1>');

    /**
     *Page Category
     *http://localhost:8000/computer
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category(Category  $category){

        // Recuperation des articles de la BDD
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category.html.twig',[
            'category' => $category
        ]);

        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();
        return $this->render('category.html.twig', [
            'products' => $products
        ]);

    }


    /**
     *Page Product
    http://localhost:8000/computer/asus_zenbook_pro_1.html
     * @Route("/{category}/{alias}_{id}.html", name="default_product", methods={"GET"})
     */
    public function product(Product $product){
        return $this->render('product.html.twig', [
            'product' => $product
        ]);
    }

}
