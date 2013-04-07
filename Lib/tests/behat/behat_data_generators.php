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
 * Data generators for acceptance testing.
 *
 * @package   core
 * @category  test
 * @copyright 2012 David Monllaó
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../behat/behat_base.php');
require_once(__DIR__ . '/../../testing/generator/lib.php');

use Behat\Gherkin\Node\TableNode as TableNode;
use Behat\Behat\Exception\PendingException as PendingException;

/**
 * Class to set up quickly a Given environment.
 *
 * Acceptance tests are block-boxed, so this steps definitions should only
 * be used to set up the test environment as we are not replicating user steps.
 *
 * All data generators should be in lib/testing/generator/* and shared between phpunit
 * and behat and they should be called from here, if possible using the standard
 * 'create_$elementname($options)' and if not possible (data generators arguments will not be
 * always the same) create an adapter 'adapt_$elementname($options)' that uses the data generator.
 *
 * @todo      If the available elements list grows too much this class must be split into smaller pieces
 * @package   core
 * @category  test
 * @copyright 2012 David Monllaó
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_data_generators extends behat_base {

    protected $datagenerator;

    /**
     * Each element specifies:
     * - The data generator sufix used.
     * - The required fields.
     * - The mapping between other elements references and database field names.
     * @var array
     */
    protected static $elements = array(
        'users' => array(
            'datagenerator' => 'user',
            'required' => array('username')
        ),
        'categories' => array(
            'datagenerator' => 'category',
            'required' => array('idnumber'),
            'switchids' => array('category' => 'parent')
        ),
        'courses' => array(
            'datagenerator' => 'course',
            'required' => array('shortname'),
            'switchids' => array('category' => 'category')
        ),
        'groups' => array(
            'datagenerator' => 'group',
            'required' => array('idnumber', 'course'),
            'switchids' => array('course' => 'courseid')
        ),
        'groupings' => array(
            'datagenerator' => 'grouping',
            'required' => array('idnumber', 'course'),
            'switchids' => array('course' => 'courseid')
        ),
        'course enrolments' => array(
            'datagenerator' => 'enrol_user',
            'required' => array('user', 'course', 'role'),
            'switchids' => array('user' => 'userid', 'course' => 'courseid', 'role' => 'roleid')

        ),
        'group members' => array(
            'datagenerator' => 'group_member',
            'required' => array('user', 'group'),
            'switchids' => array('user' => 'userid', 'group' => 'groupid')
        ),
        'grouping groups' => array(
            'datagenerator' => 'grouping_group',
            'required' => array('grouping', 'group'),
            'switchids' => array('grouping' => 'groupingid', 'group' => 'groupid')
        )
    );

    /**
     * Creates the specified element. More info about available elements in http://docs.moodle.org/dev/Acceptance_testing#Fixtures.
     *
     * @Given /^the following "(?P<element_string>(?:[^"]|\\")*)" exists:$/
     *
     * @throws Exception
     * @throws PendingException
     * @param string    $elementname The name of the entity to add
     * @param TableNode $data
     */
    public function the_following_exists($elementname, TableNode $data) {

        if (empty(self::$elements[$elementname])) {
            throw new PendingException($elementname . ' data generator is not implemented');
        }

        $this->datagenerator = testing_util::get_data_generator();

        $elementdatagenerator = self::$elements[$elementname]['datagenerator'];
        $requiredfields = self::$elements[$elementname]['required'];
        if (!empty(self::$elements[$elementname]['switchids'])) {
            $switchids = self::$elements[$elementname]['switchids'];
        }

        foreach ($data->getHash() as $elementdata) {

            // Check if all the required fields are there.
            foreach ($requiredfields as $requiredfield) {
                if (!isset($elementdata[$requiredfield])) {
                    throw new Exception($elementname . ' requires the field ' . $requiredfield . ' to be specified');
                }
            }

            // Switch from human-friendly references to ids.
            if (isset($switchids)) {
                foreach ($switchids as $element => $field) {
                    $methodname = 'get_' . $element . '_id';

                    // Not all the switch fields are required, default vars will be assigned by data generators.
                    if (isset($elementdata[$element])) {
                        // Temp $id var to avoid problems when $element == $field.
                        $id = $this->{$methodname}($elementdata[$element]);
                        unset($elementdata[$element]);
                        $elementdata[$field] = $id;
                    }
                }
            }

            // Preprocess the entities that requires a special treatment.
            if (method_exists($this, 'preprocess_' . $elementdatagenerator)) {
                $elementdata = $this->{'preprocess_' . $elementdatagenerator}($elementdata);
            }

            // Creates element.
            $methodname = 'create_' . $elementdatagenerator;
            if (method_exists($this->datagenerator, $methodname)) {
                // Using data generators directly.
                $this->datagenerator->{$methodname}($elementdata);

            } else if (method_exists($this, 'adapt_' . $elementdatagenerator)) {
                // Using an adaptor to use the data generator.
                $this->{'adapt_' . $elementdatagenerator}($elementdata);
            } else {
                throw new PendingException($elementname . ' data generator is not implemented');
            }
        }

    }

    /**
     * If password is not set it uses the username.
     * @param array $data
     * @return array
     */
    protected function preprocess_user($data) {
        if (!isset($data['password'])) {
            $data['password'] = $data['username'];
        }
        return $data;
    }


    /**
     * Adapter to enrol_user() data generator.
     * @throws Exception
     * @param mixed $data
     * @return void
     */
    protected function adapt_enrol_user($data) {

        if (empty($data['roleid'])) {
            throw new Exception('\'course enrolments\' requires the field \'role\' to be specified');
        }

        if (!isset($data['userid'])) {
            throw new Exception('\'course enrolments\' requires the field \'user\' to be specified');
        }

        if (!isset($data['courseid'])) {
            throw new Exception('\'course enrolments\' requires the field \'course\' to be specified');
        }

        if (!isset($data['enrol'])) {
            $data['enrol'] = 'manual';
        }

        $this->datagenerator->enrol_user($data['userid'], $data['courseid'], $data['roleid'], $data['enrol']);
    }

    /**
     * Gets the user id from it's username.
     * @throws Exception
     * @param string $idnumber
     * @return int
     */
    protected function get_user_id($username) {
        global $DB;

        if (!$id = $DB->get_field('user', 'id', array('username' => $username))) {
            throw new Exception('The specified user with username "' . $username . '" does not exists');
        }
        return $id;
    }

    /**
     * Gets the role id from it's shortname.
     * @throws Exception
     * @param string $idnumber
     * @return int
     */
    protected function get_role_id($roleshortname) {
        global $DB;

        if (!$id = $DB->get_field('role', 'id', array('shortname' => $roleshortname))) {
            throw new Exception('The specified role with shortname"' . $roleshortname . '" does not exists');
        }

        return $id;
    }

    /**
     * Gets the category id from it's idnumber.
     * @throws Exception
     * @param string $idnumber
     * @return int
     */
    protected function get_category_id($idnumber) {
        global $DB;

        // If no category was specified use the data generator one.
        if ($idnumber == false) {
            return null;
        }

        if (!$id = $DB->get_field('course_categories', 'id', array('idnumber' => $idnumber))) {
            throw new Exception('The specified category with idnumber "' . $idnumber . '" does not exists');
        }

        return $id;
    }

    /**
     * Gets the course id from it's shortname.
     * @throws Exception
     * @param string $shortname
     * @return int
     */
    protected function get_course_id($shortname) {
        global $DB;

        if (!$id = $DB->get_field('course', 'id', array('shortname' => $shortname))) {
            throw new Exception('The specified course with shortname"' . $shortname . '" does not exists');
        }
        return $id;
    }

    /**
     * Gets the group id from it's idnumber.
     * @throws Exception
     * @param string $idnumber
     * @return int
     */
    protected function get_group_id($idnumber) {
        global $DB;

        if (!$id = $DB->get_field('groups', 'id', array('idnumber' => $idnumber))) {
            throw new Exception('The specified group with idnumber "' . $idnumber . '" does not exists');
        }
        return $id;
    }

    /**
     * Gets the grouping id from it's idnumber.
     * @throws Exception
     * @param string $idnumber
     * @return int
     */
    protected function get_grouping_id($idnumber) {
        global $DB;

        if (!$id = $DB->get_field('groupings', 'id', array('idnumber' => $idnumber))) {
            throw new Exception('The specified grouping with idnumber "' . $idnumber . '" does not exists');
        }
        return $id;
    }
}
