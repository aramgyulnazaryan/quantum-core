<?php

/**
 * Quantum PHP Framework
 * 
 * An open source software development framework for PHP
 * 
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.0.0
 */

namespace Quantum\Libraries\Dumper;

use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * Dumper class
 * 
 * @package Quantum
 * @subpackage Libraries.Dumper
 * @category Libraries
 * @uses VarDumper
 */
class Dumper {

    public static function dump($var) {
        $cloner = new VarCloner;

        $htmlDumper = new HtmlDumper;

        $htmlDumper->setStyles([
            'default' => 'background-color:#FFFFFF; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: break-all',
            'public' => 'color:#222222',
            'protected' => 'color:#222222',
            'private' => 'color:#222222',
        ]);

        $dumper = PHP_SAPI === 'cli' ? new CliDumper : $htmlDumper;

        $dumper->dump($cloner->cloneVar($var));
    }

}
