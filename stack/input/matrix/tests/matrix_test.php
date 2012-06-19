<?php
// This file is part of Stack - http://stack.bham.ac.uk/
//
// Stack is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Stack is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Stack.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for the stack_algebra_input class.
 *
 * @copyright  2012 The University of Birmingham
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../../tests/test_base.php');
require_once(dirname(__FILE__) . '/../../factory.class.php');
require_once(dirname(__FILE__) . '/../matrix.class.php');


/**
 * Unit tests for stack_algebra_input.
 *
 * @copyright  2012 The University of Birmingham
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @group qtype_stack
 */
class stack_matrix_input_test extends qtype_stack_testcase {

	public function test_render_blank() {
		$el = stack_input_factory::make('matrix', 'ans1', 'M');
		$this->assertEquals('<table class="matrixtable" style="display:inline; vertical-align: middle;" border="0" cellpadding="1" cellspacing="0"><tbody><tr><td style="border-width: 2px 0px 0px 2px; padding-top: 0.5em">&nbsp;</td><td><input type="text" name="ans1|0|0" value="" size="5" ></td><td><input type="text" name="ans1|0|1" value="" size="5" ></td><td><input type="text" name="ans1|0|2" value="" size="5" ></td><td style="border-width: 2px 2px 0px 0px; padding-top: 0.5em">&nbsp;</td></tr><tr><td style="border-width: 0px 0px 2px 2px;">&nbsp;</td><td><input type="text" name="ans1|1|0" value="" size="5" ></td><td><input type="text" name="ans1|1|1" value="" size="5" ></td><td><input type="text" name="ans1|1|2" value="" size="5" ></td><td style="border-width: 0px 2px 2px 0px; padding-bottom: 0.5em">&nbsp;</td></tr></tbody></table>',
				$el->render(new stack_input_state(stack_input::VALID, '', '', '', ''),
						'ans1', false, 'matrix([1,2,3],[3,4,5])'));
	}
	
	
}
