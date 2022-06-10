<?php

namespace ChuckNorris\Repository;

use ChuckNorris\Entity\Category;
use ChuckNorris\Entity\CategoryRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Http\Client\ClientInterface;
use RuntimeException;

/**
 * Репозиторий для работы с категориями через API
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Http клиент
     *
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @param ClientInterface $client - http-клиент
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function getCategories(): array
    {
        try {
            $response = $this->client->request('GET', 'categories');
        } catch (GuzzleException $e) {
            throw new RuntimeException('При запросе к API произошла ошибка ' . $e);
        }

        $dataForCreate = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        return $this->createEntityCollection($dataForCreate);
    }

    /**
     * Создание сущности из данных полученных от API
     *
     * @param array $dataForCreate
     *
     * @return array
     */
    private function createEntityCollection(array $dataForCreate): array
    {
        $entityCollection = [];
        foreach ($dataForCreate['value'] as $value){
            $entityCollection[] = new Category($value);
        }

        return $entityCollection;
    }
}