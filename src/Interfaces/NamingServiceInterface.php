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

interface NamingServiceInterface
{
    /**
     * @param string $fQCN
     * @param string $primaryKey
     *
     * @return string
     */
    public static function getQualifiedFilename(string $fQCN, string $primaryKey): string;
}
