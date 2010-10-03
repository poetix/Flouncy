<?php

/**
 * ArrayRecorderTest.php
 *
 * @author Dominic Fox dominic.fox@gmail.com
 * @license http://opensource.org/licenses/lgpl-3.0.html GNU Lesser General Public License
 * @copyright 2010 Dominic Fox
 * @version 0.1
 */

include 'PathSetup.php';
require_once 'ArrayRecorder.php';

class ArrayRecorderTest extends PHPUnit_Framework_TestCase {

    public function testEmptyCallInsertsNull() {
            $arr = ArrayRecorder::start()
                ->key1()
                ->__invoke();
        $this->assertNull($arr['key1']);
    }

    public function testSingleParameterCallInsertsValue() {
        $arr = ArrayRecorder::start()
            ->key1('value1')
            ->__invoke();
        $this->assertEquals('value1', $arr['key1']);
    }

    public function testMultiParamCallInsertsArray() {
        $arr = ArrayRecorder::start()
            ->key1('value1', 'value2')
            ->__invoke();
        $this->assertTrue(is_array($arr['key1']));
        $this->assertEquals(2, count($arr['key1']));
        $this->assertEquals('value1', $arr['key1'][0]);
        $this->assertEquals('value2', $arr['key1'][1]);
    }

    public function testInsertsChain() {
        $arr = ArrayRecorder::start()
            ->key1('value1')
            ->key2('value2')
            ->__invoke();
        $this->assertEquals('value1', $arr['key1']);
        $this->assertEquals('value2', $arr['key2']);
    }

    public function testSubkeyCreation() {
        $arr = ArrayRecorder::start()
            ->top_level
                ->second_level
                    ->key1('value1')
            ->__invoke();
        $this->assertTrue(is_array($arr['top_level']));
        $this->assertTrue(is_array($arr['top_level']['second_level']));
        $this->assertEquals('value1',
                $arr['top_level']['second_level']['key1']);
    }

    public function testUnderscoreEndsSubArray() {
        $arr = ArrayRecorder::start()
            ->top_level
                ->second_level
                    ->key1('value1')
                    ->_
                ->key2('value2')
            ->__invoke();
        $this->assertEquals('value1',
                $arr['top_level']['second_level']['key1']);
        $this->assertEquals('value2',
                $arr['top_level']['key2']);
    }
}
?>
