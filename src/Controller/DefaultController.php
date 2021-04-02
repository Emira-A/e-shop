<?php


namespace App\Controller;


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
        return $this->render('home.html.twig');
    }

    /**
     *Page Category
     *http://localhost:8000/computer
     * @Route("/{alias}", name="default_category", methods={"GET"})
     */
    public function category($alias){
        return $this->render('category.html.twig',[
            'alias' => $alias
        ]);
    }

    /**
     *Page Product
    http://localhost:8000/computer/asus_zenbook_pro_1.html
     * @Route("/{category}/{alias}_{id}.html", name="default_product", methods={"GET"})
     */
    public function product($alias, $id){
        return $this->render('product.html.twig', [
            'alias' => $alias,
            'id' => $id
        ]);
    }

}