<?php

namespace Infrastructure\Mongo;

use Domain\Repository\EmailTemplateRepositoryInterface;
use Domain\Model\EmailTemplate;
use MongoDB\BSON\ObjectId;

class EmailTemplateRepository implements EmailTemplateRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('emailTemplates');
    }

    public function findByCode(string $code): ?EmailTemplate
    {
        $doc = $this->collection->findOne(['code' => $code]);
        return $doc ? $this->map($doc) : null;
    }

    public function save(EmailTemplate $template): void
    {
        $data = [
            'code' => $template->getCode(),
            'label' => $template->getLabel(),
            'subject' => $template->getSubject(),
            'body' => $template->getBody(),
            'variables' => $template->getVariables(),
            'recipients' => $template->getRecipients(),
            'active' => $template->isActive(),
            'trigger' => $template->getTrigger(),
        ];
        if ($template->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($template->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    private function map($doc): EmailTemplate
    {
        return new EmailTemplate(
            (string) $doc['_id'],
            $doc['code'],
            $doc['label'],
            $doc['subject'],
            $doc['body'],
            $doc['variables'] ?? [],
            $doc['recipients'] ?? [],
            $doc['active'],
            $doc['trigger']
        );
    }
}
