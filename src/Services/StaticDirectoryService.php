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

use eArc\Data\Exceptions\DataException;
use eArc\DataFilesystem\Interfaces\DirectoryServiceInterface;
use eArc\DataFilesystem\ParameterInterface;

abstract class StaticDirectoryService implements DirectoryServiceInterface
{
    public static function forceChdir(string $fQCN, $mod = ''): void
    {
        $absolutePath = self::getPath($fQCN, $mod);

        if (!is_dir($absolutePath)) {
            if (!mkdir($absolutePath, 0777, true)) {
                throw new DataException(sprintf('{9a8d74d3-1494-4656-b65a-35f6547b759f} Cannot make dir %s.', $absolutePath));
            }
        }

        if (!chdir($absolutePath)) {
            throw new DataException(sprintf('{82d14315-a533-4b54-aa37-93d9ba83e6b5} Cannot change to dir %s.', $absolutePath));
        }
    }

    public static function getPath(string $fQCN, string $mod = ''): string
    {
        return di_param(ParameterInterface::DATA_PATH).str_replace('\\', '/', $fQCN).$mod.'/';
    }
}
