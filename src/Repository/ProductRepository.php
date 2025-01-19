<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PictureService;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    private Connection $connection;
    private SluggerInterface $slugger;  // Declare the slugger

    public function __construct(ManagerRegistry $registry, Connection $connection, SluggerInterface $slugger)
    {
        parent::__construct($registry, Product::class);
        $this->connection = $connection;
        $this->slugger = $slugger;  // Initialize the slugger
    }

    /**
     * Trouver un produit avec toutes ses images associées en utilisant SQL brut.
     */
    /*  public function findWithImages(int $id): ?array
    {
        $sql = '
            SELECT p.*, i.id AS image_id, i.name AS image_name, i.src AS image_src, i.alt_text AS image_alt_text
            FROM product p
            LEFT JOIN image i ON i.product_id = p.id
            WHERE p.id = :id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('id', $id);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        if (!$result) {
            return null;
        }

        // Regrouper les données pour que le produit et ses images soient bien organisés
        $product = [
            'id' => $result[0]['id'],
            'name' => $result[0]['name'],
            'images' => [],
        ];

        foreach ($result as $row) {
            if ($row['image_id']) {
                $product['images'][] = [
                    'id' => $row['image_id'],
                    'name' => $row['image_name'],
                    'src' => $row['image_src'],
                    'altText' => $row['image_alt_text'],
                ];
            }
        }

        return $product;
    }
 */

    /**
     * Récupérer tous les nouveaux produits non vendus, triés par date de création
     *
     * @return Product[]
     */
    public function findAllNewProduct(): array
    {
        return $this->findBy(
            ['isSold' => false],  // Filtrer par isSold = 0 (non vendu)
            ['createdAt' => 'DESC'],         // Trier par date de création (du plus récent au plus ancien)
        );
    }

    /**
     * Récupérer les 9 premiers produits non vendus, triés par date de création
     *
     * @return Product[]
     */
    public function findAllProductActifLimit(): array
    {
        return $this->findBy(
            ['isSold' => false],  // Filtrer par isSold = 0 (non vendu)
            ['createdAt' => 'DESC'],         // Trier par date de création (du plus récent au plus ancien)
            9                                 // Limiter à 9 produits
        );
    }

    /**
     * Récupérer tous les produits non vendus uniquement les bébés 0 à 23 mois
     *
     * @return Product[]
     */
    public function findAllBabyProduct(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isSold = :isSold')  // Filtrer par isSold = false (non vendu)
            ->andWhere('p.size >= :minSize')  // Filtrer par taille minimale (0 mois)
            ->andWhere('p.size <= :maxSize')  // Filtrer par taille maximale (23 mois)
            ->setParameter('isSold', false)  // Paramètre isSold
            ->setParameter('minSize', 0)  // Paramètre taille minimale
            ->setParameter('maxSize', 23)  // Paramètre taille maximale
            ->orderBy('p.createdAt', 'DESC')  // Trier par date de création (du plus récent au plus ancien)
            ->getQuery()
            ->getResult();
    }
    /**
     * Récupérer tous les produits qui ont entre 36 et 192 mois, qui ne sont pas vendus, et uniquement gender (enum) girl et unisex
     *
     * @return Product[]
     */
    public function findAllGirlOrUnisexProduct(): array
    {
        return $this->createQueryBuilder('p')  // Assuming 'p' is the alias for Product
            ->where('p.isSold = :isSold')            // Filter for unsold products
            ->andWhere('p.size >= 36 AND p.size <= 192') // 3 years (36 months) to 16 years (192 months)
            ->andWhere('p.gender IN (:genders)')      // Filter for gender being 'girl' or 'unisex'
            ->setParameter('isSold', false)           // Bind 'isSold' to false (unsold products)
            ->setParameter('genders', ['Fille', 'Mixte']) // Bind 'genders' to 'girl' and 'unisex'
            ->getQuery()
            ->getResult(); // This will return an array of Product objects
    }

    /**
     * Récupérer tous les produits qui ont entre 36 et 192 mois, qui ne sont pas vendus, et uniquement gender (enum) girl et unisex
     *
     * @return Product[]
     */
    public function findAllBoyOrUnisexProduct(): array
    {
        return $this->createQueryBuilder('p')  // Assuming 'p' is the alias for Product
            ->where('p.isSold = :isSold')            // Filter for unsold products
            ->andWhere('p.size >= 36 AND p.size <= 192') // 3 years (36 months) to 16 years (192 months)
            ->andWhere('p.gender IN (:genders)')      // Filter for gender being 'girl' or 'unisex'
            ->setParameter('isSold', false)           // Bind 'isSold' to false (unsold products)
            ->setParameter('genders', ['Garçon', 'Mixte']) // Bind 'genders' to 'boy' and 'unisex'
            ->getQuery()
            ->getResult(); // This will return an array of Product objects
    }

    // Deplacer mes informations ProductController vers ma méthode saveProduct

    public function addProduct(Product $product, $images, $em, $slugger, PictureService $pictureService)
    {
        /* foreach ($images as $image) {

            $folder = 'products';
            $fichier = $pictureService->add($image, $folder, 300, 300);

            $img = new Image();
            
            $name = pathinfo($fichier, PATHINFO_FILENAME);
            $img->setName($name);
            $slug = $this->slugger->slug($name);
            $img->setSlug($slug);
            $img->setAltText('Image de ' . $name);
            $img->setSrc($fichier);

            $product->addImage($img);
        }
 */
        foreach ($images as $image) {
            // On définit le dossier de destination
            $folder = 'products';

            // On appelle le service d'ajout
            $fichier = $pictureService->add($image, $folder, 300, 300);

            $img = new Image();
            $img->setName($fichier);
            $product->addImage($img);
        }

        // On génère le slug
        $slug = $slugger->slug($product->getName());
        $product->setSlug($slug);

        // On arrondit le prix 
        // $prix = $product->getPrice() * 100;
        // $product->setPrice($prix);

        // On stocke
        $em->persist($product);
        $em->flush();
    }
}
