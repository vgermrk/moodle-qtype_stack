<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains tests that walk Stack questions through various sequences
 * of student interaction with different behaviours.
 *
 * @package   qtype_stack
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/questionlib.php');
require_once(dirname(__FILE__) . '/test_base.php');


/**
 * Unit tests for the Stack question type.
 *
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @group qtype_stack
 */
class qtype_stack_walkthrough_adaptive_test extends qtype_stack_walkthrough_test_base {

    public function test_test0_validate_then_submit_right_first_time() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/What is/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '2', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '2', 'ans1_val' => '2', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('firsttree', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_wrong_answer() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '3', 'ans1_val' => '3', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test0_validate_then_submit_question() {

        // Create the stack question 'test0'.
        $q = test_question_maker::make_question('stack', 'test0');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->render();

        // Process a validate request.
        $this->process_submission(array('ans1' => '1+1', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('firsttree', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '1+1', 'ans1_val' => '1+1', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(0);
        $this->check_prt_score('firsttree', 0, 0.3);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('firsttree');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_validate_then_submit_right_first_time() {

        // Create the stack question 'test1'.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->assertEquals('adaptivemultipart',
                $this->quba->get_question_attempt($this->slot)->get_behaviour_name());
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Notice here we get away with including single letter question variables in the answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit of the correct answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1);
        $this->check_prt_score('PotResTree_1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_validate_wrong_validate_right_submit_right() {

        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit, but with a changed answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Process a submit with the correct answer.
        $this->process_submission(array('ans1' => '(v-a)^(n+1)/(n+1)+c', 'ans1_val' => '(v-a)^(n+1)/(n+1)+c', '-submit' => 1));

        // Verify.
        $this->check_current_mark(1);
        $this->check_prt_score('PotResTree_1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(v-a)^(n+1)/(n+1)+c');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test1_invalid_valid_but_wrong_with_specific_feedback() {

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/missing right/')
        );

        // Valid answer.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1)', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Submit known mistake - look for specific feedback.
        $this->process_submission(array('ans1' => 'n*(v-a)^(n-1)', 'ans1_val' => 'n*(v-a)^(n-1)', '-submit' => 1));

        $this->check_current_mark(0);
        $this->check_prt_score('PotResTree_1', 0, 0.25);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'n*(v-a)^(n-1)');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('PotResTree_1');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/differentiated instead!/')
        );
    }

    public function test_test1_invalid_student_uses_question_variables() {

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test1');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                new question_pattern_expectation('/Find/'),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Process a validate request.
        // Invalid answer.
        $this->process_submission(array('ans1' => 'ta', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'ta');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/is forbidden/')
        );

        $this->process_submission(array('ans1' => 'ta1', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('PotResTree_1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'ta1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
            new question_pattern_expectation('/is forbidden/')
        );
    }

    public function test_test3_repeat_wrong_response_only_penalised_once() {
        // The scenario is this: (we use only the ans3 part of test3, leaving the others blank.)
        //
        //  Resp. State Try Raw mark Mark Penalty
        // 1. x   valid -   -        -    -
        // 2. x   score X   0.5      0.5  0.1
        // 3. x   score -   -        -    -
        // 4. x+1 valid -   -        -    -
        // 5. x+1 score X   0        0.5  0.1
        // 6. x   valid -   -        -    -
        // 7. x   score X   0.5      0.5  0.0
        // 8. 0   valid -   -        -    -
        // 9. 0   score X   1        0.8  0.0
        //
        // When reading this test, note that check_prt_score checks the score
        // returned by get_prt_result, which is the low-level routine that
        // calculates the score for the PRT given the response. The
        // ...->get_behaviour_var('_tries_oddeven') bit checks to see if the
        // behaviour acutally counted this response as a try.

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3_penalty0_1');

        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt($this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_does_not_contain_feedback_expectation()
        );

        // Validate ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', '-submit' => 1));

        $this->check_current_mark(null);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt($this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertEquals(1, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->assertContains('Your answer is not an even function. Look, \[ f(x)-f(-x)=2\\cdot x \neq 0.\]', $this->currentoutput);

        // Score ans3 => 'x'. (put it an ans1 to validate, to force the creation of a new step.)
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', 'ans1' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => 'x + 1'.
        $this->process_submission(array('ans3' => 'x + 1', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x + 1');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x + 1'.
        $this->process_submission(array('ans3' => 'x + 1', 'ans3_val' => 'x + 1', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0, 0.1);
        $this->assertEquals(2, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x + 1');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x + 1', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => 'x'.
        $this->process_submission(array('ans3' => 'x', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', 0.5, 0.1);
        $this->assertEquals(3, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();

        // Validate ans3 => '0'.
        $this->process_submission(array('ans3' => '0', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_mark(0.5);
        $this->check_prt_score('oddeven', null, null);
        $this->assertNull($this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Score ans3 => '0'.
        $this->process_submission(array('ans3' => '0', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_mark(0.8);
        $this->check_prt_score('oddeven', 1, 0);
        $this->assertEquals(4, $this->quba->get_question_attempt(
                $this->slot)->get_last_step()->get_behaviour_var('_tries_oddeven'));
        $this->render();
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_stray_placeholders();
    }

    public function test_test3_sumbit_and_finish_before_validating() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('odd', null, null);
        $this->check_prt_score('even', null, null);
        $this->check_prt_score('oddeven', null, null);
        $this->check_prt_score('unique', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Save a partially correct, partially complete response.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => 'x', 'ans4' => ''));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('odd', null, null);
        $this->check_prt_score('even', null, null);
        $this->check_prt_score('oddeven', null, null);
        $this->check_prt_score('unique', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit all and finish before validating.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedpartial);
        $this->check_current_mark(2.5);
        $this->check_prt_score('odd', 1, 0, true);
        $this->check_prt_score('even', 1, 0, true);
        $this->check_prt_score('oddeven', 0.5, 0.4, true);
        $this->check_prt_score('unique', null, null, true);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', 'x', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
    }

    public function test_test3_save_invalid_response_correct_then_stubmit() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Try to submit a response:
        // 1. right, not yet validated
        // 2. invalid
        // 3. right, not yet validated
        // 4. right, validation not required.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '(x +', 'ans3' => '0', 'ans4' => 'true', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '(x +');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit again without editing. 1. and 3. bits should now be graded.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '(x +', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(3.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '(x +');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Now fix the response to 2. and submit. Previously invalid bit should only be validated, not graded yet.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit again. Should now all be graded (and right).
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
                                        'ans1_val' => 'x^3', 'ans2_val' => 'x^2', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(4.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit all and finish - should update state from complete to gradedright.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedright);
        $this->check_current_mark(4.0);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', '0', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
    }

    public function test_test3_complex_scenario() {
        // Here are the sequence of responses we are going to test. When
        // a particular PRT generates a grades, that is shown in brackets as
        // raw fraction - penalty.
        //
        //     odd         even        oddeven       unique     Mark so far
        //  1. x^3         -           x             -          -
        //  2. x^3 (1)     -           x   (0.5)     -          1.5
        //  3. (x          (x          x+1           F (0)      1.5
        //  4. x)          x^2         x+1 (0-0.1)   -          1.5
        //  5. x^2         x           x^5           -          1.5
        //  6. x^2 (0-0)   x^2         x^5 (0.5-0.2) T (1-0.1)  2.4
        //  7. x           x^2 (1)     x+3           T (1-0.1)  3.4
        //  8. x   (1-0.1) -           x+3 (0-0.3)   T (1-0.1)  3.4
        //  9. x^3         x^2         0             T (1-0.1)  3.4
        // 10. x^3 (1-0.1) x^2 (1-0.0) 0   (1-0.4)   T (1-0.1)  3.5
        //
        // Best mark
        //     1.0         1.0         0.6           0.9        3.5
        //
        // Hopefully this summary makes the following easier to understand.

        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'test3_penalty0_1');
        $this->start_attempt_at_question($q, 'adaptive', 4);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_contains_text_input('ans2');
        $this->check_output_contains_text_input('ans3');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 1.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '', 'ans3' => 'x', 'ans4' => '', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 2.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => '', 'ans3' => 'x', 'ans4' => '',
            'ans1_val' => 'x^3', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 3.
        $this->process_submission(array('ans1' => '(x', 'ans2' => '(x', 'ans3' => 'x+1', 'ans4' => 'false',
            'ans1_val' => 'x^3', 'ans3_val' => 'x', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', '(x');
        $this->check_output_contains_text_input('ans2', '(x');
        $this->check_output_contains_text_input('ans3', 'x+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'false', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 4.
        $this->process_submission(array('ans1' => 'x)', 'ans2' => 'x^2', 'ans3' => 'x+1', 'ans4' => '',
            'ans3_val' => 'x+1', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x)');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x+1');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 5.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x', 'ans3' => 'x^5', 'ans4' => '',
            'ans2_val' => 'x^2', 'ans3_val' => 'x+1', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(1.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^2');
        $this->check_output_contains_text_input('ans2', 'x');
        $this->check_output_contains_text_input('ans3', 'x^5');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_does_not_contain_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), '', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 6.
        $this->process_submission(array('ans1' => 'x^2', 'ans2' => 'x^2', 'ans3' => 'x^5', 'ans4' => 'true',
            'ans1_val' => 'x^2', 'ans2_val' => 'x', 'ans3_val' => 'x^5', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(2.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^2');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x^5');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 7.
        $this->process_submission(array('ans1' => 'x', 'ans2' => 'x^2', 'ans3' => 'x+3', 'ans4' => 'true',
            'ans1_val' => 'x^2', 'ans2_val' => 'x^2', 'ans3_val' => 'x^5', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', 'x+3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 8.
        $this->process_submission(array('ans1' => 'x', 'ans2' => '', 'ans3' => 'x+3', 'ans4' => 'true',
            'ans1_val' => 'x', 'ans2_val' => 'x^2', 'ans3_val' => 'x+3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x');
        $this->check_output_contains_text_input('ans2', '');
        $this->check_output_contains_text_input('ans3', 'x+3');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 9.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
            'ans1_val' => 'x', 'ans3_val' => 'x+3', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(3.4);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_does_not_contain_prt_feedback('odd');
        $this->check_output_does_not_contain_prt_feedback('even');
        $this->check_output_does_not_contain_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Step 10.
        $this->process_submission(array('ans1' => 'x^3', 'ans2' => 'x^2', 'ans3' => '0', 'ans4' => 'true',
            'ans1_val' => 'x^3', 'ans2_val' => 'x^2', 'ans3_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(3.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3');
        $this->check_output_contains_text_input('ans2', 'x^2');
        $this->check_output_contains_text_input('ans3', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', true),
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Submit all and finish - should update state from complete to gradedright.
        $this->quba->finish_all_questions();

        $this->check_current_state(question_state::$gradedright);
        $this->check_current_mark(3.5);
        $this->render();
        $this->check_output_contains_text_input('ans1', 'x^3', false);
        $this->check_output_contains_text_input('ans2', 'x^2', false);
        $this->check_output_contains_text_input('ans3', '0', false);
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_input_validation('ans2');
        $this->check_output_contains_input_validation('ans3');
        $this->check_output_does_not_contain_input_validation('ans4');
        $this->check_output_contains_prt_feedback('odd');
        $this->check_output_contains_prt_feedback('even');
        $this->check_output_contains_prt_feedback('oddeven');
        $this->check_output_contains_prt_feedback('unique');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_contains_select_expectation('ans4', stack_boolean_input::get_choices(), 'true', false),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );
    }

    public function test_divide_by_0() {
        // Create a stack question.
        $q = test_question_maker::make_question('stack', 'divide');
        $this->start_attempt_at_question($q, 'adaptive', 1);

        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1');
        $this->check_output_does_not_contain_input_validation();
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_current_output(
                $this->get_does_not_contain_feedback_expectation(),
                $this->get_does_not_contain_num_parts_correct(),
                $this->get_no_hint_visible_expectation()
        );

        // Validate the response 0.
        $this->process_submission(array('ans1' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Now submit the response 0. Causes a divide by 0.
        $this->process_submission(array('ans1' => '0', 'ans1_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$invalid);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '0');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('prt1');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_output_contains_lang_string('stackCas_CASError', 'qtype_stack');

        // Validate the response 1/2 (correct).
        $this->process_submission(array('ans1' => '1/2', 'ans1_val' => '0', '-submit' => 1));

        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_prt_score('prt1', null, null);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1/2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_does_not_contain_prt_feedback();
        $this->check_output_does_not_contain_stray_placeholders();

        // Now submit the response 1/2.
        $this->process_submission(array('ans1' => '1/2', 'ans1_val' => '1/2', '-submit' => 1));

        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(1); // No penalties applied.
        $this->check_prt_score('prt1', 1, 0);
        $this->render();
        $this->check_output_contains_text_input('ans1', '1/2');
        $this->check_output_contains_input_validation('ans1');
        $this->check_output_contains_prt_feedback('prt1');
        $this->check_output_does_not_contain_stray_placeholders();
        $this->check_output_contains_lang_string('stackCas_CASError', 'qtype_stack');
    }
}
