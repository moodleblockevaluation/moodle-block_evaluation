<?php
/*  DOCUMENTATION
    .............

    The actual display of your block is block_evaluation.php

    init() method is essential part to pass the class variables:
    $this->title: to display the title in the header of your block.
    $this->version (optional unless you need Moodle to perform automatic updates) and there is no return value to be expected
    from init().

    $CFG stands for Configuration. CFG is a global variable can be used in any moodle page, contains Moodle's
    root, data(moodledata) and database configuration settings and other config values.

    get_string converts an array of string names to localised strings for a specific plugin. It looks formal when you code
    with language strings instead of manual text. It's a good habit of writing manual text to strings.

    has_config() method states that the block has a settings.php file. This method specifies whether your block wants to
    present additional configuration settings.

    get_content method should define $this->content variable of your block.
    If $this->content_type is BLOCK_TYPE_TEXT, then $this->content is expected to have the following member variables:
    text - a string of arbitrary length and content displayed inside the main area of the block, and can contain HTML.
    footer - a string of arbitrary length and content displayed below the text, using a smaller font size.
    It can also contain HTML.

    instance_allow_multiple() method indicates whether you want to allow multiple block instances in the same page or not.
    If you do allow multiple instances, it is assumed that you will also be providing per-instance configuration for the
    block.

*/

// Class name must be named exactly the block folder name.
class block_evaluation extends block_base {
    
    function init() {

        global $CFG;
        $this->title = get_string('evalBlock', 'block_evaluation');
    }

    function has_config() {
        return true;
    }

    public function get_content() {
        global $DB;
        global $USER;
        global $CFG;
        global $PAGE;
        global $OUTPUT;
     
        if ($this->content !== null) {
            return $this->content;
        }
        
        $this->content = new stdClass;
        
        $userid = $USER->id;
 
        // Userstatus für Darstellung ermitteln  STUD => Studierende; MA => Lehrende
        $status = $DB->get_field_sql('SELECT institution FROM {user} where id = '.$userid);
       
 	$username = $USER->username;       

	$settings_infotext = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="infotext"');

        
        //role=student
        
        if ($status == 'STUD') {
        //sql querie if feedback was finished
        $settings_timeopen = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_timeopen"');
        
        $settings_timeclose = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_timeclose"');
        
        $settings_namelike = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_namelike"');
        
        
        $sql='SELECT f.id fid, m.id mid, f.course feedbackcourse, from_unixtime(timeopen) begin, From_unixtime(timeclose) end, f.name feedbackname, c.fullname coursename, k.path coursecategories

        ,(SELECT count(*) from {feedback_completed} WHERE userid = '.$userid.' and feedback=f.id) AS ausgefuellt

        FROM {feedback} f, {course} c, {course_categories} k, {course_modules} m, {modules} m2
        WHERE (timeopen >=UNIX_TIMESTAMP("'.$settings_timeopen.'") OR timeopen = \'0\')
        AND (timeclose <=UNIX_TIMESTAMP("'.$settings_timeclose.'") OR timeclose = \'0\')
        AND f.course = c.id
        AND c.category = k.id
        AND m2.name = \'feedback\'
        AND m.module = m2.id
        AND m.visible != 0
        AND m.course = f.course
        AND m.instance = f.id
        AND f.name like \'%'.$settings_namelike.'%\'

AND c.id in (SELECT ic.id
FROM {course}           ic
JOIN {context}          con ON con.instanceid = ic.id
JOIN {role_assignments} ra  ON ra.contextid   = con.id    AND con.contextlevel = 50
JOIN {role}             r   ON ra.roleid      = r.id
JOIN {user}             u   ON u.id           = ra.userid
WHERE u.id = '.$userid. '
  AND ic.id = ic.id
  AND ra.roleid = 5) 

        ORDER BY c.fullname, f.name';
     

        $datasql = $DB->get_records_sql($sql);
        
        

        //table
        $tableHTML = "<table class=\"table table-bordered\"><thead><tr><th>".get_string('tableheader_1', 'block_evaluation')."<th>".get_string('tableheader_2', 'block_evaluation')."<th>".get_string('tableheader_3', 'block_evaluation')."<th>".get_string('tableheader_4', 'block_evaluation')."</th></tr></thead><tbody>";        
        foreach ($datasql as $e) {
          $dateformat = DateTime::createFromFormat('Y-m-d H:i:s', $e->end)->format('d.m.y H:i');
          $feedbackurl  = new moodle_url("/mod/feedback/view.php?id=".$e->mid."");
          $dataArr=(array(
                                        $e->coursename,
                                        "<a href = $feedbackurl target = _blank>$e->feedbackname</a>",
                                        $dateformat,
                                        $e->ausgefuellt));        
          if ($dataArr[3]==0)
            {$tableHTML .= "<tr><td>$dataArr[0]<td>$dataArr[1]<td>$dataArr[2]<td align=\"center\"><img src=\"".$CFG->wwwroot."/blocks/evaluation/pix/kreuz.png\" width=15></td></tr>";}
            else{$tableHTML .= "<tr><td>$dataArr[0]<td>$dataArr[1]<td>$dataArr[2]<td align=\"center\"><img src=\"".$CFG->wwwroot."/blocks/evaluation/pix/haken.png\" width=15></td></tr>";}
        }; 
        $tableHTML .= "</tbody></table></html>";
        
        //print content in block
        $this->content->text = $settings_infotext;
        $this->content->text .= $tableHTML;
	$this->content->text .= "<a href='https://support.hs-nb.de/otrs/public.pl?Action=PublicFAQZoom;ItemID=78' target='_blank'>FAQ</a>";
//        $this->content->text .= "Userid: ".$userid.", Rolle: ".$userrole;

        //echo "<script>console.log(JSON.parse('".json_encode($datasql)."'));</script>";
        
        
        //role = manager, coursecreator, editingteacher, teacher, dekan
        }  elseif ($status == 'MA') {
        
        //sql querie of how many feedbacks are given in assigned courses for teachers
        $settings_timeopen = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_timeopen"');
        
        $settings_timeclose = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_timeclose"');
        
        $settings_namelike = $DB->get_field_sql('SELECT value FROM {config_plugins}
        where plugin="block_evaluation"
        and name="settings_namelike"');
        
	
        
        $sql='SELECT f.id fid, m.id mid, f.course feedbackcourse, from_unixtime(timeopen) begin, From_unixtime(timeclose) end, f.name feedbackname, c.fullname coursename, k.path     coursecategories

        ,(SELECT count(distinct(ra.userid)) AS Users FROM {role_assignments} AS ra
JOIN {context} AS ctx ON ra.contextid = ctx.id
JOIN {user_enrolments} AS ue on ue.userid = ra.userid
JOIN {enrol} AS e ON e.id = ue.enrolid
JOIN {user} AS u ON u.id = ue.userid
WHERE ra.roleid = 5 AND ctx.instanceid = c.id AND e.courseid = c.id
and ue.status = 0
and (ue.timeend = 0 or ue.timeend >= unix_timestamp())
and u.suspended = 0
        ) AS studentssum,

        (SELECT COUNT(*) 

        FROM {feedback_completed} AS mfc

        WHERE mfc.feedback = f.id

        GROUP BY feedback) AS feedbacksum

        FROM {feedback} f, {course} c, {course_categories} k, {course_modules} m, {modules} m2

        WHERE (timeopen >=UNIX_TIMESTAMP("'.$settings_timeopen.'") OR timeopen = \'0\')

        AND (timeclose <=UNIX_TIMESTAMP("'.$settings_timeclose.'") OR timeclose = \'0\')

        AND f.course = c.id

        AND c.category = k.id

        AND m2.name = \'feedback\'

        AND m.module = m2.id

        AND m.course = f.course

        AND m.instance = f.id

        AND f.name like \'%'.$settings_namelike.'%\'

	AND f.name like \'%'.$username.'%\'

        AND c.id IN (SELECT e.courseid FROM {user_enrolments} ue join {enrol} AS e on e.id = ue.enrolid join {course} AS c on c.id = e.courseid where userid = '.$userid.')

        ORDER BY c.fullname, f.name';

        $datasql = $DB->get_records_sql($sql);
        
        

        //table
        $tableHTML = "<table class=\"table table-bordered\"><thead><tr><th>".get_string('tableheader_1', 'block_evaluation')."<th>".get_string('tableheader_2', 'block_evaluation')."<th>".get_string('tableheader_3', 'block_evaluation')."<th>Studenten insgesamt</th><th>".get_string('tableheader_4', 'block_evaluation')."</th></tr></thead><tbody>";        
        foreach ($datasql as $e) {
          $dateformat = DateTime::createFromFormat('Y-m-d H:i:s', $e->end)->format('d.m.y H:i');
          $feedbackurl  = new moodle_url("/mod/feedback/view.php?id=".$e->mid."");
          $dataArr=(array(
                                        $e->coursename,
                                        "<a href = $feedbackurl target = _blank>$e->feedbackname</a>",
                                        $dateformat,
                                        $e->studentssum,
                                        $e->feedbacksum));        
        $tableHTML .= "<tr><td>$dataArr[0]<td>$dataArr[1]<td>$dataArr[2]<td>$dataArr[3]<td>$dataArr[4]</td></tr>";
        }; 
        $tableHTML .= "</tbody></table></html>";
        
        //print content in block
	$this->context->text = $settings_infotext;
        $this->content->text .= $tableHTML;
  	$this->content->text .= "<a href='https://support.hs-nb.de/otrs/public.pl?Action=PublicFAQZoom;ItemID=78' target='_blank'>FAQ</a>";

        //$this->content->text .= "Userid: ".$username.", Rolle: ".$userrole;
          }
        
        
        
        else {
          $this->content->text = "Keine Berechtigung zur Ansicht";
          }
        


        return $this->content;
    }
}

