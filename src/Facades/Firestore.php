<?php

namespace Firemoo\Firemoo\Facades;

use Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array createCollection(string $name, ?string $parentCollectionId = null, ?string $parentDocumentId = null)
 * @method static array getCollections(?string $parentCollectionId = null, ?string $parentDocumentId = null)
 * @method static array getCollection(string $collectionId)
 * @method static array deleteCollection(string $collectionId)
 * @method static array createDocument(string $collectionId, array $data, ?string $documentId = null)
 * @method static array getDocuments(string $collectionId, int $page = 1, int $limit = 10)
 * @method static array getDocument(string $collectionId, string $documentId, bool $firestoreFormat = false, ?string $projectId = null, ?string $databaseId = null)
 * @method static array updateDocument(string $collectionId, string $documentId, array $data)
 * @method static array deleteDocument(string $collectionId, string $documentId)
 *
 * @see \App\Services\FirestoreService
 */
class Firestore extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface::class;
    }
}
