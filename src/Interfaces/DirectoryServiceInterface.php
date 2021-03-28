<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data
 * @link https://github.com/Koudela/eArc-data/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataFilesystem\Interfaces;

use eArc\Data\Exceptions\Interfaces\DataExceptionInterface;

interface DirectoryServiceInterface
{
    /**
     * @param string $fQCN
     * @param string $mod
     *
     * @throws DataExceptionInterface
     */
    public static function forceChdir(string $fQCN, $mod = ''): void;

    /**
     * @param string $fQCN
     * @param string $mod
     *
     * @return string
     */
    public static function getPath(string $fQCN, string $mod = ''): string;
}
