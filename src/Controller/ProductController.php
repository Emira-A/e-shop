<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/dashboard/product")
 */
class ProductController extends AbstractController
{
    /**
     * http://localhost:8001/dashboard/product/create
     * @IsGranted("ROLE_AUTHOR")
     * @Route("/product/create", name="product_create", methods={"GET|POST"})
     */
    public function create(Request $request, SluggerInterface $slugger)
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

            ->add('reference', TextType::class, [
                'label' => "Référence",
                'attr' => [
                    'placeholder' => "Ref du produit"
                ]
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

            $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){

            # Upload de l'image
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();
            # récupère les données de notre image de notre formulaire (elle seronts stocké dans notre variable image)

            #si on a bien quelque chose uploadé
            if ($image) {
                #on récupère le nom du fichier
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                #on crée un nom sécurisé -> un slug
                $safeFilename = $slugger->slug($originalFilename);
                #generer un id unique ce qui donne un nouveau nom de fichier
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // stocker nos images - on modifie le directory avec images
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    # TODO Traitement en cas d'erreur de l'upload
                }
                #On sauvegarde dans la BDD le nom du nouveau fichier
                $product->setImage($newFilename);
            }

            # Génération de l'alias
            $product->setAlias(
                $slugger->slug(
                    $product->getTitle()
                )
            );

            # Sauvegarde dans la BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            # Notification de confirmation
            $this->addFlash('success', "Félicitations, votre article est en ligne.");



            # Redirection vers le nouvel article (je prends en compte les paramètres ici category, alias et id
            return $this->redirectToRoute('default_product', [
                'category' => $product->getCategories()[0]->getAlias(),
                'alias' => $product->getAlias(),
                'id' => $product->getId()
            ]);
        }


        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);



    }

}
