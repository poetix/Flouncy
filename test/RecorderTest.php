<?php
/**
 * RecorderTest.php
 *
 * @author Dominic Fox dominic.fox@gmail.com
* @license http://opensource.org/licenses/lgpl-3.0.html GNU Lesser General Public License
 * @copyright 2010 Dominic Fox
 * @version 0.1
 * @package com.codepoetics.fluency.test
 */
include 'PathSetup.php';
require_once 'Recorder.php';

class RecorderTest extends PHPUnit_Framework_TestCase {

    public function testRecordGet() {
        $r = new Recorder();
        $r->test_value;
        $actions = $r();
        $this->assertEquals(1, count($actions),
                "Only one action should be recorded");
        $this->assertEquals('test_value', $actions[0],
                "Get action is recorded as a string");
    }
}
