<?php

namespace Test\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\queryBuilder;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
	public function myFindAll(){
	   /*
		* $queryBuilder = $this->createQueryBuilder('a');
		* $query = $queryBuilder->getQuery();
		* $result =$query->getResult();
		* return $result;
		*/

		//ou 

		return $this
			->createQueryBuilder('a')
			->getQuery()
			->getResult();

	   /*
		* dans un controlleur : 
		* $listAdvert = $repository->myFindAll();
		*/	

	}

	public function myFindOne($id){
		$qb = $this->createQueryBuilder('a');
		$qb
			->where('a.id = :id') // je défini un parametre dans la requête
			->setParameter('id', $id); // je lui attribue une valeur

		return $qb->getQuery->getResult;	
	}

	public function findByAuthorAndDate($author, $year){

		$qb = $this->createQueryBuilder('a');
		$qb
			->where('a.author = :author')
			->setParameter('author', $author)
			->andWhere('a.date < :year')
			->setParameter('year', $year)
			->orderBy('a.date', 'DESC');

		return $qb
			->getQuery()
			->getResult();	

	}

}
