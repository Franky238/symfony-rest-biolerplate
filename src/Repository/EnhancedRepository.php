<?php


namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class EnhancedRepository extends ServiceEntityRepository
{
    const NOT_FOUND_MESSAGE = 'The resource cannot be found';
    const ALREADY_EXISTS_MESSAGE = 'A resource already exists';

    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * Finds a resource by identifier.
     *
     * @param int      $id          The resource identifier
     * @param int|null $lockMode    One of the \Doctrine\DBAL\LockMode::* constants or NULL
     * @param int|null $lockVersion The lock version.
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    public function findOrFail($id, $lockMode = null, $lockVersion = null)
    {
        $entity = $this->find($id, $lockMode, $lockVersion);
        if (null === $entity) {
            throw new NotFoundHttpException(
                sprintf('%s (\'id\' = %d)', self::NOT_FOUND_MESSAGE, $id)
            );
        }
        return $entity;
    }
    /**
     * Find resource by criteria or fail.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array
     *
     * @throws NotFoundHttpException
     */
    public function findByOrFail(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy, $limit, $offset);
        if (count($entities) == 0) {
            throw new NotFoundHttpException(
                sprintf('%s (%s)', self::NOT_FOUND_MESSAGE, $this->implodeCriteria($criteria))
            );
        }
        return $entities;
    }
    /**
     * Find resource by criteria or create a new.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object
     *
     * @throws NotFoundHttpException
     */
    public function findOneByOrFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);
        if (null === $entity) {
            throw new NotFoundHttpException(
                sprintf('%s (%s)', self::NOT_FOUND_MESSAGE, $this->implodeCriteria($criteria))
            );
        }
        return $entity;
    }
    /**
     * Check for existing resource by criteria and fail.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object
     *
     * @throws UnprocessableEntityHttpException
     */
    public function findOneByAndFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);
        if (null !== $entity) {
            throw new UnprocessableEntityHttpException(
                sprintf('%s (%s)', self::ALREADY_EXISTS_MESSAGE, $this->implodeCriteria($criteria))
            );
        }
        return $entity;
    }

    /**
     * Get criteria as string.
     *
     * @param array $criteria The query criteria
     *
     * @return string
     */
    private function implodeCriteria(array $criteria)
    {
        if (true === empty($criteria)) {
            return 'no fields';
        }
        $keys = implode(', ', array_keys($criteria));
        $values = implode(', ', array_values($criteria));
        return sprintf("%s' = '%s'", $keys, $values);
    }
}