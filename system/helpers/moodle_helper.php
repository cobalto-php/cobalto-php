<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Cria novo curso no Moodle
 * @autor Cauê Duarte
 * @cursos array contendo arrays curso com os seguintes campos:
 * @fullname string   //full name
 * @shortname string   //course short name
 * @categoryid int   //category id
 * @idnumber string  Opcional //id number
 * @summary string  Opcional //summary
 * @summaryformat int  Padrão para "1" //summary format (1 = HTML, 0 = MOODLE, 2 = PLAIN or 4 = MARKDOWN)
 * @format string  Padrão para "weeks" //course format: weeks, topics, social, site,..
 * @showgrades int  Padrão para "1" //1 if grades are shown, otherwise 0
 * @newsitems int  Padrão para "5" //number of recent items appearing on the course page
 * @startdate int  Opcional //timestamp when the course start
 * @numsections int  Padrão para "10" //number of weeks/topics
 * @maxbytes int  Padrão para "2097152" //largest size of file that can be uploaded into the course
 * @showreports int  Padrão para "0" //are activity report shown (yes = 1, no =0)
 * @visible int  Opcional //1: available to student, 0:not available
 * @hiddensections int  Padrão para "0" //How the hidden sections in the course are displayed to students
 * @groupmode int  Padrão para "0" //no group, separate, visible
 * @groupmodeforce int  Padrão para "0" //1: yes, 0: no
 * @defaultgroupingid int  Padrão para "0" //default grouping id
 * @enablecompletion int  Opcional //Enabled, control via completion and activity settings. Disabled,
  not shown in activity settings.
 * @completionstartonenrol int  Opcional //1: begin tracking a student's progress in course completion after
  course enrolment. 0: does not
 * @completionnotify int  Opcional //1: yes 0: no
 * @lang string  Opcional //forced course language
 * @forcetheme string  Opcional //name of the force theme
 */
function createCursosMoodle($cursos) {
    $url = 'http://moodlecgic.ufpel.edu.br/webservice/rest/server.php';
    $wstoken = '81da278e423f7799edf49971177b5715';
    $wsfunction = 'core_course_create_courses';
    $format = 'json';
    $params = array('courses' => $cursos);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url . '?wstoken=' . $wstoken . '&wsfunction=' . $wsfunction . '&moodlewsrestformat=' . $format);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}

/**
 * Exclui curso no Moodle
 * @autor Cauê Duarte
 * @courseids array contendo id do curso à deletar
 * */
function deleteCursosMoodle($courseids) {
    $url = 'http://moodlecgic.ufpel.edu.br/webservice/rest/server.php';
    $wstoken = '81da278e423f7799edf49971177b5715';
    $wsfunction = 'core_course_delete_courses';
    $format = 'json';
    $curl = curl_init();
    
    //$params = array('courseids' => $courseids);
    $params =  $courseids;
    logVar($courseids);
    
    curl_setopt($curl, CURLOPT_URL, $url . '?wstoken=' . $wstoken . '&wsfunction=' . $wsfunction . '&moodlewsrestformat=' . $format);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result);
}