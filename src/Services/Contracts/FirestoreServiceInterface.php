<?php

namespace Firemoo\Firemoo\Services\Contracts;

interface FirestoreServiceInterface
{
    /**
     * Create a new collection
     *
     * @param string $name
     * @param string|null $parentCollectionId
     * @param string|null $parentDocumentId
     * @return array
     * @throws \Exception
     */
    public function createCollection(string $name, ?string $parentCollectionId = null, ?string $parentDocumentId = null): array;

    /**
     * Get all collections
     *
     * @param string|null $parentCollectionId
     * @param string|null $parentDocumentId
     * @return array
     * @throws \Exception
     */
    public function getCollections(?string $parentCollectionId = null, ?string $parentDocumentId = null): array;

    /**
     * Get a single collection by ID
     *
     * @param string $collectionId
     * @return array
     * @throws \Exception
     */
    public function getCollection(string $collectionId): array;

    /**
     * Delete a collection
     *
     * @param string $collectionId
     * @return array
     * @throws \Exception
     */
    public function deleteCollection(string $collectionId): array;

    /**
     * Create a document in a collection
     *
     * @param string $collectionId
     * @param array $data
     * @param string|null $documentId
     * @return array
     * @throws \Exception
     */
    public function createDocument(string $collectionId, array $data, ?string $documentId = null): array;

    /**
     * Get all documents in a collection
     *
     * @param string $collectionId
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \Exception
     */
    public function getDocuments(string $collectionId, int $page = 1, int $limit = 10): array;

    /**
     * Get a single document
     *
     * @param string $collectionId
     * @param string $documentId
     * @param bool $firestoreFormat
     * @param string|null $projectId
     * @param string|null $databaseId
     * @return array
     * @throws \Exception
     */
    public function getDocument(string $collectionId, string $documentId, bool $firestoreFormat = false, ?string $projectId = null, ?string $databaseId = null): array;

    /**
     * Update a document
     *
     * @param string $collectionId
     * @param string $documentId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateDocument(string $collectionId, string $documentId, array $data): array;

    /**
     * Delete a document
     *
     * @param string $collectionId
     * @param string $documentId
     * @return array
     * @throws \Exception
     */
    public function deleteDocument(string $collectionId, string $documentId): array;
}
