<?php

namespace Firemoo\Firemoo\Services;

use Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface;
use Firemoo\Firemoo\Services\Contracts\HttpClientServiceInterface;
use Firemoo\Firemoo\Services\Contracts\LoggerServiceInterface;
use Exception;

class FirestoreService implements FirestoreServiceInterface
{
    private HttpClientServiceInterface $httpClient;
    private LoggerServiceInterface $logger;

    public function __construct(
        HttpClientServiceInterface $httpClient,
        LoggerServiceInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * Create a new collection
     */
    public function createCollection(string $name, ?string $parentCollectionId = null, ?string $parentDocumentId = null): array
    {
        try {
            $payload = ['name' => $name];

            if ($parentCollectionId !== null) {
                $payload['parent_collection_id'] = $parentCollectionId;
            }

            if ($parentDocumentId !== null) {
                $payload['parent_document_id'] = $parentDocumentId;
            }

            $response = $this->httpClient->request('POST', '/api/collections', [
                'json' => $payload,
            ]);

            $this->logger->info("Collection created: {$name}", ['collection_id' => $response['data']['id'] ?? null]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to create collection: {$e->getMessage()}", ['name' => $name]);
            throw $e;
        }
    }

    /**
     * Get all collections
     */
    public function getCollections(?string $parentCollectionId = null, ?string $parentDocumentId = null): array
    {
        try {
            $query = [];

            if ($parentCollectionId !== null) {
                $query['parent_collection_id'] = $parentCollectionId;
            }

            if ($parentDocumentId !== null) {
                $query['parent_document_id'] = $parentDocumentId;
            }

            $response = $this->httpClient->request('GET', '/api/collections', [
                'query' => $query,
            ]);

            return $response['data']['collections'] ?? [];
        } catch (Exception $e) {
            $this->logger->error("Failed to get collections: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get a single collection by ID
     */
    public function getCollection(string $collectionId): array
    {
        try {
            $response = $this->httpClient->request('GET', "/api/collections/{$collectionId}");

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to get collection: {$e->getMessage()}", ['collection_id' => $collectionId]);
            throw $e;
        }
    }

    /**
     * Delete a collection
     */
    public function deleteCollection(string $collectionId): array
    {
        try {
            $response = $this->httpClient->request('DELETE', "/api/collections/{$collectionId}");

            $this->logger->info("Collection deleted", ['collection_id' => $collectionId]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to delete collection: {$e->getMessage()}", ['collection_id' => $collectionId]);
            throw $e;
        }
    }

    /**
     * Create a document in a collection
     */
    public function createDocument(string $collectionId, array $data, ?string $documentId = null): array
    {
        try {
            $payload = ['data' => $data];

            if ($documentId !== null) {
                $payload['document_id'] = $documentId;
            }

            $response = $this->httpClient->request('POST', "/api/collections/{$collectionId}/documents", [
                'json' => $payload,
            ]);

            $this->logger->info("Document created", [
                'collection_id' => $collectionId,
                'document_id' => $response['data']['document_id'] ?? null,
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to create document: {$e->getMessage()}", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);
            throw $e;
        }
    }

    /**
     * Get all documents in a collection
     */
    public function getDocuments(string $collectionId, int $page = 1, int $limit = 10): array
    {
        try {
            $response = $this->httpClient->request('GET', "/api/collections/{$collectionId}/documents", [
                'query' => [
                    'page' => $page,
                    'limit' => $limit,
                ],
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to get documents: {$e->getMessage()}", [
                'collection_id' => $collectionId,
                'page' => $page,
                'limit' => $limit,
            ]);
            throw $e;
        }
    }

    /**
     * Get a single document
     */
    public function getDocument(string $collectionId, string $documentId, bool $firestoreFormat = false, ?string $projectId = null, ?string $databaseId = null): array
    {
        try {
            $query = [];

            if ($firestoreFormat) {
                $query['format'] = 'firestore';
                if ($projectId !== null) {
                    $query['project_id'] = $projectId;
                }
                if ($databaseId !== null) {
                    $query['database_id'] = $databaseId;
                }
            }

            $response = $this->httpClient->request('GET', "/api/collections/{$collectionId}/documents/{$documentId}", [
                'query' => $query,
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to get document: {$e->getMessage()}", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);
            throw $e;
        }
    }

    /**
     * Update a document
     */
    public function updateDocument(string $collectionId, string $documentId, array $data): array
    {
        try {
            $response = $this->httpClient->request('PUT', "/api/collections/{$collectionId}/documents/{$documentId}", [
                'json' => ['data' => $data],
            ]);

            $this->logger->info("Document updated", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to update document: {$e->getMessage()}", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a document
     */
    public function deleteDocument(string $collectionId, string $documentId): array
    {
        try {
            $response = $this->httpClient->request('DELETE', "/api/collections/{$collectionId}/documents/{$documentId}");

            $this->logger->info("Document deleted", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to delete document: {$e->getMessage()}", [
                'collection_id' => $collectionId,
                'document_id' => $documentId,
            ]);
            throw $e;
        }
    }
}
