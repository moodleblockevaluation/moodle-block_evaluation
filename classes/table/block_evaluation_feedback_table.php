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

namespace block_evaluation\table;

use core_table\dynamic as dynamic_table;
use flexible_table;
use stdClass;

/**
 * Class block_evaluation_feedback_table
 *
 * @package    block_evaluation
 * @copyright  Neubrandenburg University of Applied Sciences <support_moodle@hs-nb.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_evaluation_feedback_table extends flexible_table implements dynamic_table {
    /**
     * Constructor for the evaluation block feedback table.
     *
     * @param string $uniqueid The table unique id
     */
    public function __construct(string $uniqueid) {
        $this->collapsible(true);
        $this->setup();
        $this->set_attribute('class', 'flexible table table-striped table-hover');
    }

    /**
     * Get the columns for the table.
     *
     * @return string[]
     */
    protected function get_column_list(): array {
        return [
            'tableheader_1' => get_string('tableheader_1', 'block_evaluation'),
            'tableheader_2' => get_string('tableheader_2', 'block_evaluation'),
            'tableheader_3' => get_string('tableheader_3', 'block_evaluation'),
            'tableheader_4' => get_string('tableheader_4', 'block_evaluation'),
        ];
    }

    /**
     * Get the table content.
     */
    public function get_content(): string {
        ob_start();
        $this->out();
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Print the table.
     */
    public function out(): void {
        // Add the row data.
        $this->add_data(['1', '2', '3', '4']);
    }

    #[\Override]
    public function is_downloadable($downloadable = null): bool {
        return false;
    }

    #[\Override]
    public function guess_base_url(): void {
        $url = new \moodle_url('/');
        $this->define_baseurl($url);
    }

    #[\Override]
    public function has_capability(): bool {
        return has_capability('moodle/user:editownprofile', \context_system::instance());
    }
}
