<?php

/**
 * Copyright (c) 2014 Shivam Dixit <shivamd001@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * or (at your option) any later version, as published by the Free
 * Software Foundation
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public
 * License along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace webgoat;

/**
 * Main logic of the lesson
 *
 * Lesson Name: XPATH Injection
 */
class XPATHInjection extends BaseLesson
{
    /**
     * Get title of the lesson
     *
     * @return string Returns the title
     */
    public function getTitle()
    {
        return "XPATH Injection";
    }

    /**
     * Get category of the lesson
     *
     * @return string Returns the lesson category
     */
    public function getCategory()
    {
        return "Injection Flaws";
    }

    /**
     * Starting point of the lesson
     */
    public function start()
    {
        $this->hints = array(
            'The data is stored in XML format',
            'The system is using XPath to query',
            'XPath is almost the same thing as SQL, the same hacking techniques apply too',
        );

        $filePath = dirname(__FILE__)."/employees.xml";
        $this->htmlContent .= file_get_contents(__DIR__."/content.html");

        if (isset($_POST['submit'])) {

            $xml = simplexml_load_file($filePath);

            try {
                $employees = $xml->xpath("/employees/employee[loginID='$_POST[username]' and passwd='$_POST[pass]']");

                if (count($employees) == 0) {
                    $this->addErrorMessage("Login Failed.");
                }

                if (count($employees) > 1) {
                    // If the submission is correct
                    $this->setCompleted(true);
                }

                foreach ($employees as $employee) {
                    $this->htmlContent .= "
                <tr>
                    <td>$employee->loginID</td>
                    <td>$employee->accountno</td>
                    <td>$employee->salary</td>
                </tr>";
                }
            } catch (\Exception $e) {
                $this->addErrorMessage("Login Failed.");
            }
        }
        $this->htmlContent .= "</tbody></table></div></div><br>";
    }

    /**
     * Reset the lesson
     */
    public function reset()
    {
        $this->setCompleted(false);
        return true;
    }
}
