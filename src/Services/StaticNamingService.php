<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data
 * @link https://github.com/Koudela/eArc-data/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataFilesystem\Services;

use eArc\DataFilesystem\Interfaces\NamingServiceInterface;

abstract class StaticNamingService implements NamingServiceInterface
{
    public static function getQualifiedFilename(string $fQCN, string $primaryKey): string
    {
        return di_static(StaticDirectoryService::class)::getPath($fQCN).$primaryKey.'.txt';
    }
}
