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
        $r = Recorder::start()->test_value;
        $actions = $r();
        $this->assertEquals(1, count($actions),
                "Only one action is recorded");
        $this->assertEquals('test_value', $actions[0],
                "Get action is recorded as a string");
    }

    public function testRecordCall() {
        $r = Recorder::start()
            ->test_call('param1', 'param2');
        $actions = $r();
        $this->assertEquals(1, count($actions),
                "Only one action is recorded");
        $this->assertTrue(is_array($actions[0]),
                "Call action is recorded as an actionsay");
        $this->assertEquals('test_call', $actions[0][0],
                "First actionsay element is method name");
        $this->assertTrue(is_array($actions[0][1]),
                "Second actionsay element is actionsay of parameters");
        $this->assertEquals(2, count($actions[0][1]),
                "Both parameters are recorded");
        $this->assertEquals('param1', $actions[0][1][0],
                "First parameter value is recorded");
        $this->assertEquals('param2', $actions[0][1][1],
                "Second parameter value is recorded");
    }

    public function testCallsChain() {
        $r = Recorder::start()
            ->call1('param1')
            ->call2('param2');
        $actions = $r();
        $this->assertEquals(2, count($actions),
                "Both calls are recorded");
        $this->assertEquals('call1', $actions[0][0]);
        $this->assertEquals('param1', $actions[0][1][0]);
        $this->assertEquals('call2', $actions[1][0]);
        $this->assertEquals('param2', $actions[1][1][0]);
    }

    public function testGetsChain() {
        $r = Recorder::start()
            ->get1
            ->get2;
        $actions = $r();
        $this->assertEquals(2, count($actions),
                "Both gets are recorded");
        $this->assertEquals('get1', $actions[0],
                "First action is recorded");
        $this->assertEquals('get2', $actions[1],
                "Second action is recorded");
    }

    public function testMixedActions() {
        $r = Recorder::start()
            ->call1('param1')
            ->get1
            ->call2('param2')
            ->get2;
        $actions = $r();
        $this->assertEquals(4, count($actions),
                "All four actions are recorded");
        $this->assertEquals('call1', $actions[0][0]);
        $this->assertEquals('param1', $actions[0][1][0]);
        $this->assertEquals('get1', $actions[1],
                "First action is recorded");
        $this->assertEquals('call2', $actions[2][0]);
        $this->assertEquals('param2', $actions[2][1][0]);
        $this->assertEquals('get2', $actions[3],
                "Second action is recorded");
    }
}
