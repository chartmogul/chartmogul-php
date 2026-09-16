<?php

namespace ChartMogul;

use ChartMogul\Resource\AbstractResource;
use ChartMogul\Resource\CollectionWithCursor;
use ChartMogul\Http\ClientInterface;
use ChartMogul\Service\AllTrait;
use ChartMogul\Service\UpdateTrait;
use ChartMogul\Service\CreateTrait;
use ChartMogul\Service\DestroyTrait;
use ChartMogul\Service\GetTrait;
use ChartMogul\Service\FromArrayTrait;

/**
 * @property-read string $uuid
 * @property-read string|null $customer_uuid
 * @property-read string|null $data_source_uuid
 * @property-read string $customer_external_id
 * @property-read string|null $external_id
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read integer $position
 * @property-read string $email
 * @property-read string $title
 * @property-read string $notes
 * @property-read string $phone
 * @property-read string $linked_in
 * @property-read string $twitter
 * @property-read string|null $last_active_at
 * @property-read string|null $last_seen
 * @property-read string $custom
 */
class Contact extends AbstractResource
{
    use CreateTrait;
    use AllTrait;
    use GetTrait;
    use DestroyTrait;
    use UpdateTrait;
    use FromArrayTrait;

    /**
     * @ignore
     */
    public const RESOURCE_NAME = 'Contact';
    /**
     * @ignore
     */
    public const RESOURCE_PATH = '/v1/contacts';
    public const RESOURCE_ID = 'contact_uuid';
    public const ROOT_KEY = 'entries';

    protected $uuid;
    protected $customer_uuid;
    protected $data_source_uuid;
    protected $customer_external_id;
    protected $external_id;
    protected $first_name;
    protected $last_name;
    protected $position;
    protected $email;
    protected $title;
    protected $notes;
    protected $phone;
    protected $linked_in;
    protected $twitter;
    protected $last_active_at;
    protected $last_seen;
    protected $custom;

    /**
     * Merge Contacts
     *
     * @param  string               $into
     * @param  string               $from
     * @param  ClientInterface|null $client
     * @return Contact
     */
    public static function merge($into, $from, ?ClientInterface $client = null)
    {
        $result = (new static([], $client))
            ->getClient()
            ->send("/v1/contacts/".$into."/merge/".$from, "POST");

        return new Contact($result, $client);
    }

    /**
     * Find all tasks for a contact.
     *
     * @param  array $options
     * @return CollectionWithCursor
     */
    public function tasks(array $options = [])
    {
        $client = $this->getClient();
        $options["contact_uuid"] = $this->uuid;
        $result = $client->send("/v1/tasks", "GET", $options);

        return Task::fromArray($result, $client);
    }

    /**
     * Creates a task for a contact.
     *
     * @param  array $data
     * @return Task
     */
    public function createTask(array $data = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/tasks", "POST", $this->withAssociatedObjectIdentifier($data));

        return new Task($result, $client);
    }

    /**
     * Find all entity notes for a contact.
     *
     * @param  array $options
     * @return CollectionWithCursor
     */
    public function entityNotes(array $options = [])
    {
        $client = $this->getClient();
        $options["contact_uuid"] = $this->uuid;
        $result = $client->send("/v1/notes", "GET", $options);

        return EntityNote::fromArray($result, $client);
    }

    /**
     * Creates an entity note for a contact.
     *
     * @param  array $data
     * @return EntityNote
     */
    public function createEntityNote(array $data = [])
    {
        $client = $this->getClient();
        $result = $client->send("/v1/notes", "POST", $this->withAssociatedObjectIdentifier($data));

        return new EntityNote($result, $client);
    }

    private function withAssociatedObjectIdentifier(array $data)
    {
        if (isset($data["customer_uuid"]) || isset($data["associated_object_identifier"])) {
            return $data;
        }

        $data["associated_object_identifier"] = [
            "associated_object" => "contact",
            "method" => "uuid",
            "value" => $this->uuid,
        ];

        return $data;
    }
}
