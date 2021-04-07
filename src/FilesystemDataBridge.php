<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data-filesystem
 * @link https://github.com/Koudela/eArc-data-filesystem/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataFilesystem;

use eArc\Data\Entity\Interfaces\EntityInterface;
use eArc\Data\Exceptions\DataException;
use eArc\Data\Exceptions\Interfaces\NoDataExceptionInterface;
use eArc\Data\Exceptions\NoDataException;
use eArc\Data\Manager\Interfaces\Events\OnFindInterface;
use eArc\Data\Manager\Interfaces\Events\OnLoadInterface;
use eArc\Data\Manager\Interfaces\Events\OnPersistInterface;
use eArc\Data\Manager\Interfaces\Events\OnRemoveInterface;
use eArc\DataFilesystem\Services\StaticDirectoryService;
use eArc\DataFilesystem\Services\StaticNamingService;


class FilesystemDataBridge implements OnPersistInterface, OnLoadInterface, OnRemoveInterface, OnFindInterface
{
    protected string $staticDirectoryService;
    protected string $staticNamingService;

    public function __construct()
    {
        $this->staticDirectoryService = di_static(StaticDirectoryService::class);
        $this->staticNamingService = di_static(StaticNamingService::class);
    }

    public function onPersist(array $entities): void
    {
        foreach ($entities as $entity) {
            $this->staticDirectoryService::forceChdir($entity::class);
            file_put_contents(
                $this->staticNamingService::getQualifiedFilename($entity::class, $entity->getPrimaryKey()),
                serialize($entity),
                LOCK_EX
            );
        }
    }

    public function onLoad(string $fQCN, array $primaryKeys, array &$postLoadCallables): array
    {
        if (!class_exists($fQCN)) {
            throw new DataException(sprintf(
                '{3cd59522-2f5a-45e6-afa4-ca10653e45b7} Only entities mapped by existing classes can be loaded. Class %s does not exists.',
                $fQCN
            ));
        }

        if (!is_subclass_of($fQCN, EntityInterface::class)) {
            throw new DataException(sprintf(
                '{7699797d-f092-4ab6-b58a-7f31880be980} Only entities can be loaded. But %s does not implement %s.',
                $fQCN,
                EntityInterface::class
            ));
        }

        $entities = [];

        foreach ($primaryKeys as $primaryKey) {
            $entities[$primaryKey] = $this->loadEntity($fQCN, $primaryKey);
        }

        return $entities;
    }

    public function onRemove(string $fQCN, array $primaryKeys): void
    {
        foreach ($primaryKeys as $primaryKey) {
            unlink($this->staticNamingService::getQualifiedFilename($fQCN, $primaryKey));
        }
    }

    public function onFind(string $fQCN, array $keyValuePairs): array|null
    {
        if (!empty($keyValuePairs)) {
            return null;
        }

        $this->staticDirectoryService::forceChdir($fQCN);

        $result = [];

        foreach (scandir('.') as $item) {
            if (is_file($item)) {
                $key = mb_substr($item, 0, -4);
                $result[$key] = $key;
            }
        }

        return $result;
    }

    /**
     * @param string $fQCN
     * @param string $primaryKey
     *
     * @return EntityInterface
     *
     * @throws NoDataExceptionInterface
     */
    protected function loadEntity(string $fQCN, string $primaryKey): EntityInterface
    {
        $absoluteFilePath = $this->staticNamingService::getQualifiedFilename($fQCN, $primaryKey);

        if (!$content = file_get_contents($absoluteFilePath)) {
            throw new NoDataException(sprintf('{2271084f-4c89-48b6-bc4f-f3cc7bb30313} Failed to load data for %s - %s.', $fQCN, $primaryKey));
        }

        if (!$entity = unserialize($content)) {
            throw new DataException(sprintf('{678d78e8-88f2-448b-944f-33113f0b1dbc} Failed to decode data for %s - %s.', $fQCN, $primaryKey));
        }

        if (!$entity instanceof $fQCN) {
            throw new DataException('{044f2702-1621-482b-b687-ddf5be56d03c} Loading does not yield the correct entity class.');
        }

        return $entity;
    }
}
