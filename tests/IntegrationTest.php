<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data-filesystem
 * @link https://github.com/Koudela/eArc-data-filesystem/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataFilesystemTests;

use eArc\Data\Initializer;
use eArc\Data\ParameterInterface;
use eArc\DataFilesystem\FilesystemDataBridge;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function init(): void
    {
        Initializer::init();

        di_tag(ParameterInterface::TAG_ON_LOAD, FilesystemDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_PERSIST, FilesystemDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_REMOVE, FilesystemDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_FIND, FilesystemDataBridge::class);

        di_set_param(\eArc\DataFilesystem\ParameterInterface::DATA_PATH, __DIR__.'/data');
    }

    public function testBridge(): void
    {
        $this->init();

        $entity = new MyEntity('yet-another-primary-key');
        data_persist($entity);

        self::assertSame($entity, data_load(MyEntity::class, 'yet-another-primary-key'));
        data_detach($entity::class);
        self::assertNotSame($entity, data_load(MyEntity::class, 'yet-another-primary-key'));
        self::assertEquals($entity, data_load(MyEntity::class, 'yet-another-primary-key'));

        $entities = data_find(MyEntity::class, []);
        self::assertCount(1, $entities);
        self::assertEquals(array_pop($entities), $entity->getPrimaryKey());
    }
}
