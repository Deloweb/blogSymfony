<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('a');
    }

    /**
     * @return Article[] Returns an array of Article objects associated with given tag
     */
    
    public function findByTag(Tag $tag)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.tags', 't')
            ->andWhere('t.id = :id')
            ->setParameter('id', $tag->getId())
            ->orderBy('a.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
        /**
     * @return Article[] Returns an array of Article objects associated with given tag
     */
    public function findByTagDQL(Tag $tag)
    {

        $entityManager = $this->getEntityManager();

        $querry = $entityManager->createQuery(
            'SELECT a
            FROM App\Entity\Article a
            INNER JOIN a.tag.t
            WHERE t.id = :id
            ORDER BY a.title ASC'
        )->setParameter('id', $tag->getId());
    }
    

    /**
     * @return Article[] Returns an array of Article objects associated with given category
     */
    
    public function findByCategory(Category $category)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.category', 'c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $category->getId())
            ->orderBy('a.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
