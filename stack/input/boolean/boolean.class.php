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
 * Input for entering true/false using a select dropdown.
 *
 * @copyright  2012 University of Birmingham
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class stack_boolean_input extends stack_input {
    const F = 'false';
    const T = 'true';
    const NA = '';

    public function __construct($name, $teacheranswer, $parameters) {
        if (!in_array($teacheranswer, array(self::T, self::F))) {
            $teacheranswer = self::NA;
        }
        parent::__construct($name, $teacheranswer, $parameters);
    }

    public static function get_choices() {
        return array(
            self::F => stack_string('false'),
            self::T => stack_string('true'),
            self::NA => stack_string('notanswered'),
        );
    }

    protected function extra_validation($contents) {
        if (!array_key_exists($contents[0], $this->get_choices())) {
            return get_string('booleangotunrecognisedvalue', 'qtype_stack');
        }
        return '';
    }

    public function render(stack_input_state $state, $fieldname, $readonly) {

        $attributes = array();
        if ($readonly) {
            $attributes['disabled'] = 'disabled';
        }

        return html_writer::select(self::get_choices(), $fieldname,
                $this->contents_to_maxima($state->contents), '', $attributes);
    }

    public function add_to_moodleform_testinput(MoodleQuickForm $mform) {
        $mform->addElement('select', $this->name, $this->name, self::get_choices());
        $mform->setDefault($this->name, '');
    }

    /**
     * Return the default values for the parameters.
     * @return array parameters` => default value.
     */
    public static function get_parameters_defaults() {
        return array(
                'mustVerify'     => false,
                'hideFeedback'   => true);
    }
}
