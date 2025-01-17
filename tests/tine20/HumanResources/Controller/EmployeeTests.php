<?php
/**
 * Tine 2.0 - http://www.tine20.org
 *
 * @package     HumanResources
 * @license     http://www.gnu.org/licenses/agpl.html
 * @copyright   Copyright (c) 2013 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Alexander Stintzing <a.stintzing@metaways.de>
 */

/**
 * Test class for HumanResources Controller
 */
class HumanResources_Controller_EmployeeTests extends HumanResources_TestCase
{
    /**
     * tests if the filter for the employee model gets created properly
     */
    public function testFilters()
    {
        // prepare dates
        $today = new Tinebase_DateTime();
        $oneMonthAgo = clone $today;
        $oneMonthAgo->subMonth(1);
        $oneMonthAhead = clone $today;
        $oneMonthAhead->addMonth(1);
        $twoMonthsAgo = clone $oneMonthAgo;
        $twoMonthsAgo->subMonth(1);

        $employeeController = HumanResources_Controller_Employee::getInstance();

        $employee1 = $this->_getEmployee('pwulf');
        $employee1->employment_begin = $oneMonthAgo;
        $employee1->employment_end = $oneMonthAhead;
        $employee1 = $employeeController->create($employee1);

        $employee2 = $this->_getEmployee('rwright');
        $employee2->employment_begin = $oneMonthAgo;
        $employee2 = $employeeController->create($employee2);

        $filter = new HumanResources_Model_EmployeeFilter(array(
            array('field' => 'n_given', 'operator' => 'equals', 'value' => 'Paul'),
            ['field' => 'last_modified_time', 'operator' => 'after', 'value' => ''] // this line should be ignored by the filter
        ));
        $result = $employeeController->search($filter);

        $this->assertEquals(1, $result->count());
        $this->assertEquals('Paul', $result->getFirstRecord()->n_given);

        // test employed filter

        // employee3 is not yet employed
        $employee3 = $this->_getEmployee('jmcblack');
        $employee3->employment_begin = $oneMonthAhead;
        $employee3 = $employeeController->create($employee3);

        $filter = new HumanResources_Model_EmployeeFilter(array(
            array('field' => 'employment_end', 'operator' => 'after', 'value' => $oneMonthAhead)
        ));
        $result = $employeeController->search($filter);
        $msg = 'jmcblack and rwright should have been found';
        $this->assertEquals(2, $result->count(), $msg);
        $names = $result->n_fn;
        // just jmcblack and rwright should have been found
        $this->assertContains('Roberta Wright', $names, $msg);
        $this->assertContains('James McBlack', $names, $msg);

        
        // employee4 has been employed
        $employee4 = $this->_getEmployee('jsmith');
        $employee4->employment_begin = $twoMonthsAgo;
        $employee4->employment_end = $oneMonthAgo;
        $employee4 = $employeeController->create($employee4);

        $this->assertEquals('Photographer', $employee4->position);
        
        $filter = new HumanResources_Model_EmployeeFilter(array(
            array('field' => 'is_employed', 'operator' => 'equals', 'value' => TRUE)
        ));
        $result = $employeeController->search($filter, new Tinebase_Model_Pagination([
            'sort' => 'account_id',
            'dir' => 'ASC',
            'model' => HumanResources_Model_Employee::class,
        ]));
        $msg = 'rwright and pwulf should have been found';
        $this->assertEquals(2, $result->count(), $msg);
        $this->assertSame('Roberta Wright', $result->getFirstRecord()->n_fn);
        $this->assertSame('Paul Wulf', $result->getLastRecord()->n_fn);


        $filter = new HumanResources_Model_EmployeeFilter(array(
            array('field' => 'is_employed', 'operator' => 'equals', 'value' => FALSE)
        ));
        $result = $employeeController->search($filter, new Tinebase_Model_Pagination([
            'sort' => 'account_id',
            'dir' => 'DESC',
            'model' => HumanResources_Model_Employee::class,
        ]));

        $msg = 'jsmith and jmcblack should have been found';
        $this->assertEquals(2, $result->count(), $msg);
        $this->assertSame('John Smith', $result->getFirstRecord()->n_fn);
        $this->assertSame('James McBlack', $result->getLastRecord()->n_fn);
    }
}
