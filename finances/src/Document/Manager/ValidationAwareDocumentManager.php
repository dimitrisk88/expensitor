<?php

namespace App\Document\Manager;

use ApiPlatform\ParameterValidator\Exception\ValidationException;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationAwareDocumentManager extends DocumentManager
{
    public function __construct(
        private DocumentManager $decorated,
        private ValidatorInterface $validator,
    ) {
        parent::__construct(
            $decorated->getClient(),
            $decorated->getConfiguration(),
            $decorated->getEventManager()
        );
    }

    public function flush(array $options = []): void
    {
        $this->validateEntities();
        parent::flush($options);
    }

    private function validateEntities(): void
    {
        $unitOfWork = $this->getUnitOfWork();
        $entities = array_merge(
            $unitOfWork->getScheduledDocumentInsertions(),
            $unitOfWork->getScheduledDocumentUpdates()
        );

        $errors = [];
        foreach ($entities as $entity) {
            $entityErrors = $this->validator->validate($entity);
            if (count($entityErrors) > 0) {
                $errors[get_class($entity)][] = $entityErrors;
            }
        }

        foreach ($errors as $error) {
            throw new ValidationException($error);
        }
    }
}