<?php

namespace App\SharedKernel\Infrastructure\Persistence\Doctrine;

use App\SharedKernel\Domain\Aggregate\AggregateRoot;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

abstract class MongoRepository
{
    protected DocumentManager $documentManager;
    protected DocumentRepository $repository;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        $this->repository = $this->documentManager->getRepository($this->entity());
    }

    abstract protected function entity(): string;

    protected function repository(): DocumentRepository
    {
        return $this->repository;
    }
    protected function doPersist(AggregateRoot $aggregate): void
    {
        $this->documentManager->persist($aggregate);
        $this->documentManager->flush();
    }

}
