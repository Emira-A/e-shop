<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/create", name="product_create", methods={"GET|POST"})
     */
    public function create()
    {
        $product = new Product();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneByEmail('emira@eshop.com');

        $product->setUser($user);

        $form = $this->createFormBuilder($product)
            ->add('title', TextType::class, [
                'label' => "Nom du produit",
                'attr' => [
                    'placeholder' => "Nom du produit"
                ]
            ])

            ->add('categories', EntityType::class, [
                'label' => "Choisir une catégorie",
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true #permet de faire les carrés à choix multiple
            ])

            ->add('description', TextareaType::class, [
                'label' => "Description du produit",
            ])

            ->add('prix', MoneyType::class,[
                'label' => 'Prix TTC'
            ])

            ->add('image', FileType::class, [
                'label' => 'Illustration'
            ])

            ->add( 'submit', SubmitType::class, [
                'label' => "Enregistrer le produit"
            ])

            ->getForm();

        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);



    }

}
