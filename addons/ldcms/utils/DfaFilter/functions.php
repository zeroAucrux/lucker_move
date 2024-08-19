<?php
namespace addons\ldcms\utils\DfaFilter;

use addons\ldcms\utils\DfaFilter\Exceptions\PdsSystemException;

/**
 * @param      $str
 * @param null $encoding
 *
 * @return int
 * @throws \addons\ldcms\utils\DfaFilter\Exceptions\PdsSystemException
 */
function mb_strlen($str, $encoding = null)
{
    $length = \mb_strlen($str, $encoding);
    if ($length === false) {
        throw new PdsSystemException(' encoding 无效');
    }

    return $length;
}
