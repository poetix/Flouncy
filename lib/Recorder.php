<?php
/**
 * Recorder.php
 *
 * @author Dominic Fox dominic.fox@gmail.com
 * @license http://opensource.org/licenses/lgpl-3.0.html GNU Lesser General Public License
 * @copyright 2010 Dominic Fox
 * @version 0.1
 * @package com.codepoetics.fluency
 */

/**
 * Records field access and method calls into a buffer.
 *
 * <p>Example usage:</p>
 * <code>
 * $r = new Recorder();
 * $r  ->SELECT
 *         ->customer_id
 *         ->sum('order_value')->as('tot_order_value')
 *     ->FROM
 *         ->customer_order
 *     ->GROUP_BY
 *         ->customer_id;
 *  $actions = $r();
 * </code>
 *
 * <p><kbd>$actions</kbd> will be the following array:</p>
 * <code>
 * [  SELECT,
 *    customer_id,
 *    [sum, [order_value]],
 *    [as, [tot_order_value]],
 *    FROM,
 *    customer_order,
 *    GROUP_BY,
 *    customer_id]
 * </code>
 *
 * <p>A good usage pattern is to subclass <kbd>Recorder</kbd>,
 * overriding its <kbd>__invoke()</kbd> method so that
 * it returns a more structured object based on the
 * raw action data. For instance, a <kbd>Query</kbd> class
 * might format the above array result into a valid SQL string.</p>
 *
 * @see ArrayRecorder
 */
class Recorder {
    /**
     *
     * @var array Buffer of actions captured by the Recorder.
     */
    protected $actions;

    /**
     * Factory method for syntactic convenience.
     * 
     * <p>PHP doesn't like <kbd>$r = new Recorder()->some_method()</kbd>,
     * so this method provides an alternative syntax:
     * <kbd>$r = Recorder::start()->some_method();</kbd></p>
     * 
     * @static
     * @return Recorder
     */
    public static function start() {
        return new static;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize buffer of actions.
        $this->actions = array();
    }

    /**
     * Capture a "get" for an unknown field.
     *
     * @param string $name The name of the field to get.
     * @return Recorder This object, for method chaining.
     */
     public function __get($name) {
         // Append the name to the buffer of actions
         array_push($this->actions, $name);
         return $this;
     }

     /**
      * Capture a "call" to an unknown method.
      *
      * @param string $name The name of the method that was called.
      * @param array $args An array of the arguments passed to the method.
      * @return Recorder This object, for method chaining.
      */
     public function __call($name, $args) {
         array_push($this->actions, array($name, $args));
         return $this;
     }

     /**
      * Returns the array of captured actions.
      *
      * Override this to construct some more structured object out of the raw
      * action data.
      *
      * @return array The buffered actions captured by this Recorder.
      */
     public function __invoke() {
         return $this->actions;
     }
}
?>
