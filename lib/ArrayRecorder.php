<?php

/**
 * ArrayRecorder.php
 *
 * @author Dominic Fox dominic.fox@gmail.com
 * @license http://opensource.org/licenses/lgpl-3.0.html GNU Lesser General Public License
 * @copyright 2010 Dominic Fox
 * @version 0.1
 */


require_once 'Recorder.php';

/**
 * A Recorder that returns a hierarchical array.
 */
class ArrayRecorder extends Recorder {

    /**
     * Returns a nested array derived from the recorded actions.
     *
     * @return array The nested array of values recorded by the recorder.
     */
    public function __invoke() {
        $result = array();
        $arrayPtr =& $result;
        $stack = array();
        foreach ($this->actions as $action) {
            if ($action=='_') {
                // Go back up one level in the array hierarchy
                // by popping the array pointer off the stack.
                $arrayPtr =& $this->array_rpop($stack);
            } else if (is_string($action)) {
                // Move down one level in the array hierarchy.
                // Ensure the next sub-array exists.
                if (!array_key_exists($action, $arrayPtr)) {
                    $arrayPtr[$action] = array();
                }
                // Push the current array pointer on to the stack.
                $stack[count($stack)] =& $arrayPtr;
                // Move the array pointer to the sub-array.
                $arrayPtr =& $arrayPtr[$action];
            } else {
                list($key, $params) = $action;
                if (count($params)==0) {
                    // Insert null if no parameters.
                    $arrayPtr[$key] = null;
                } else if (count($params)==1) {
                    // Insert single parameter as scalar value.
                    $arrayPtr[$key] = $params[0];
                } else {
                    // Insert multiple parameters as array
                    $arrayPtr[$key] = $params;
                }
            }
        }
        return $result;
    }

    /**
     * Pop an element off the end of an array, returning a reference
     * to the element.
     *
     * @param array $a The array to pop a reference from.
     * @return mixed Reference to the popped array element.
     */
    private function &array_rpop(&$a){
           end($a);
           $k = key($a);
           $v =& $a[$k];
           unset($a[$k]);
           return $v;
    }
}
?>