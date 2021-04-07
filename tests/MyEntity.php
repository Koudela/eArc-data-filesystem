<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data-redis
 * @link https://github.com/Koudela/eArc-data-redis/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataFilesystemTests;

use eArc\Data\Entity\AbstractEntity;

class MyEntity extends AbstractEntity
{
    public function __construct(string $primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }
}
