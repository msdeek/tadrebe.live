<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.coderfish.com.eg
 * @since      1.0.0
 *
 * @package    tadreblive
 * @subpackage tadreblive/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    tadreblive
 * @subpackage tadreblive/includes
 * @author     codefish <info@codefish.com.eg>
 */

class CPanel_Content
{

    /**
     * lerndash Post Type
     *
     * @since    1.0.0
     */
    public function learndash_post_type($post)
    {
        if ($post == 'course') {
            $post_type = 'sfwd-courses';
            $key = 'cpanel_course_id';
            return array('post_type' => $post_type, 'key' => $key);
        } elseif ($post == 'lesson') {
            $post_type = 'sfwd-lessons';
            $key = 'cpanel_topic_id';
            return array('post_type' => $post_type, 'key' => $key);
        } elseif ($post == 'topic') {
            $post_type = 'sfwd-topic';
            $key = 'cpanel_module_id';
            return array('post_type' => $post_type, 'key' => $key);
        }
    }

    /**
     * Cpanel Query
     *
     * @since    1.0.0
     */

    public function query_cpanel_posts($post_type, $key, $value)
    {

        $query_meta = array(
            'posts_per_page' => -1,
            'post_type' => $post_type,
            'meta_query' => array(
                array(
                    'key' => $key,
                    'value' => $value,
                    'compare' => '='
                )
            )
        );
        $posts = new WP_Query($query_meta);
        return $posts;
    }

    /**
     * Cpanel Cources
     *
     * @since    1.0.0
     */
    public function get_cpanel_courses($baseurl, $token)
    {
        global $wpdb;
        $post_type = $this->learndash_post_type('course');
        $key = $post_type['key'];
        $post_type = $post_type['post_type'];
        $method = 'GET';
        if (true == $token) {
            $body = array(
                'wstoken' => $token,
                'wsfunction' => 'core_course_get_courses',
                'moodlewsrestformat' => 'json'
            );
            $content = new CPanel_Services;
            $content = $content->register_moodle_services($baseurl, $method, $body);
            #var_dump( $content);
            foreach ((array)$content as $course) {
                $fullname = $course->displayname;
                $shortname = $course->shortname;
                $value = $course->id;
                $lang = $course->lang;
                #$posts = $this->query_cpanel_posts($post_type, $key, $value);
                $course_id = $wpdb->get_var( // @codingStandardsIgnoreLine
                    $wpdb->prepare(
                        "SELECT post_id
                    FROM {$wpdb->prefix}postmeta
                    WHERE meta_key = 'cpanel_course_id'
                    AND meta_value = %s",
                        $value
                    )
                );

                if (false == $course_id) {
                    $post_id = wp_insert_post(array(
                        'post_title' => $shortname,
                        'post_type' =>  $post_type,
                    ));
                    add_post_meta($post_id, $key, $value, true);
                    add_post_meta($post_id, 'fullname', $fullname, true);
                    add_post_meta($post_id, 'cplang', $lang, true);
                } else {

                    update_post_meta($course_id, 'fullname', $fullname);
                    update_post_meta($course_id, 'cplang', $lang);
                    $course_id =  wp_update_post(array(
                        'ID' => $course_id,
                        'post_title' => $shortname,
                    ));
                }
            }
        }
    }

    public function get_cpanel_items($baseurl, $token)
    {
        global $wpdb;

        $post_type = $this->learndash_post_type('course');
        $key = $post_type['key'];
        $post_type = $post_type['post_type'];
        $courses = get_posts(array(
            'fields'          => 'ids',
            'numberposts' => -1,
            'post_type' => $post_type,
            'post_status' => 'publish'
        ));
        if (0 != $token) {
            foreach ((array)$courses as $course) {
                $get_course = get_post($course);
                $post_author = $get_course->post_author;
                $moodle_course_id = get_post_meta($course, $key, true);
                $method = 'GET';
                $body = array(
                    'wstoken' => $token,
                    'wsfunction' => 'core_course_get_contents',
                    'moodlewsrestformat' => 'json',
                    'courseid' => $moodle_course_id,
                );
                $content = new CPanel_Services;
                $content = $content->register_moodle_services($baseurl, $method, $body);
                foreach ((array) $content as $lesson) {
                    $lesson_post_type = $this->learndash_post_type('lesson');
                    $lesson_key = $lesson_post_type['key'];
                    $lesson_post_type = $lesson_post_type['post_type'];
                    
                    // Read Josn Peoperty 
                    $lesson_value = $lesson->id; // Lesson ID
                    $lesson_fullname = $lesson->name;
                    $lesson_visible = $lesson->visible;
                    $lesson_summary = $lesson->summary;
                    $lesson_summaryformat = $lesson->summaryformat;
                    $lesson_section = $lesson->section;
                    $lesson_hiddenbynumsections = $lesson->hiddenbynumsections;
                    $lesson_uservisible = $lesson->uservisible;
                    
                    $modules = $lesson->modules;

                    
                    if ($lesson_visible == '1') {
                        $post_status = 'publish';
                    } else {
                        $post_status = 'private';
                    }

                    $lesson_id = $wpdb->get_var( // @codingStandardsIgnoreLine
                        $wpdb->prepare(
                            "SELECT post_id
                            FROM {$wpdb->prefix}postmeta
                            WHERE meta_key = 'cpanel_topic_id'
                            AND meta_value = %s",
                                $lesson_value
                        )
                    );

                    if (false == $lesson_id) {
                        $lesson_id = wp_insert_post(array(
                            'post_title' => $lesson_fullname,
                            'post_type' =>  $lesson_post_type,
                            'post_author' => $post_author,
                            'post_content' => $lesson_summary,
                            'menu_order' => $lesson_section,
                            'post_status' =>  $post_status,
                        ));
                        add_post_meta($lesson_id, $lesson_key, $lesson_value, true);
                        learndash_update_setting($lesson_id, 'course', $course, true);
                        add_post_meta($lesson_id, 'lesson_summaryformat', $lesson_summaryformat, true);
                        add_post_meta($lesson_id, 'lesson_hiddenbynumsections', $lesson_hiddenbynumsections, true);
                        add_post_meta($lesson_id, 'lesson_uservisible', $lesson_uservisible, true);
                    }else{
                        $lesson_id = wp_update_post(array(
                            'ID' => $lesson_id,
                            'post_title' => $lesson_fullname,
                            'post_status' =>  $post_status,
                            'menu_order' => $lesson_section,
                            'post_author' => $post_author,
                            'post_content' => $lesson_summary,
                        ));
                        update_post_meta($lesson_id, 'lesson_summaryformat', $lesson_summaryformat);
                        update_post_meta($lesson_id, 'lesson_hiddenbynumsections', $lesson_hiddenbynumsections);
                        update_post_meta($lesson_id, 'lesson_uservisible', $lesson_uservisible);
                    }


                    $module_section= 0;
                    foreach ((array) $modules as $module) {
                        //for ($module_section = 0; null != $module; $module_section+=10 );
                        $module_post_type = $this->learndash_post_type('topic');
                        $module_key = $module_post_type['key'];
                        $module_post_type = $module_post_type['post_type'];

                        // Read Josn Peoperty 
                        $module_value = $module->id;
                        $module_url = $module->url;
                        $module_fullname = $module->name;
                        $module_instance = $module->instance;
                        $module_contextid = $module->contextid;
                        $module_visible = $module->visible;
                        $modname = $module->modname;
                        
                        
                        


                        if ($module_visible == '1') {
                            $post_status = 'publish';
                        } else {
                            $post_status = 'private';
                        }
                        $module_id = $wpdb->get_var( // @codingStandardsIgnoreLine
                            $wpdb->prepare(
                                "SELECT post_id
                            FROM {$wpdb->prefix}postmeta
                            WHERE meta_key = 'cpanel_module_id'
                            AND meta_value = %s",
                                $module_value
                            )
                        );

                        if (false == $module_id) {
                            $topic_id = wp_insert_post(array(
                                'post_title' => $module_fullname,
                                'post_type' =>  $module_post_type,
                                'post_author' => $post_author,
                                'menu_order' => $module_section++,
                            ));
                            add_post_meta($topic_id, $module_key, $module_value, true);
                            learndash_update_setting($topic_id, 'course', $course, true);
                            learndash_update_setting($topic_id, 'lesson', $lesson_id, true);

                            add_post_meta($topic_id, 'modname', $modname, true);
                            add_post_meta($topic_id, 'module_instance', $module_instance, true);
                            add_post_meta($topic_id, 'module_contextid', $module_contextid, true);
                            add_post_meta($topic_id,'module_url', $module_url, true);
                        } else {
                            $topic_id = wp_update_post(array(
                                'ID' => $module_id,
                                'post_title' => $module_fullname,
                                'post_status' =>  $post_status,
                                'post_author' => $post_author,
                                'menu_order' => $module_section++,
                            ));
                            update_post_meta($topic_id, 'modname', $modname);
                            update_post_meta($topic_id, 'module_instance', $module_instance);
                            update_post_meta($topic_id, 'module_contextid', $module_contextid);
                            update_post_meta($topic_id,'module_url', $module_url);
                        }
                    }
                }
            }
        }
           
    }
    public function bigbluebuttonbn_add()
    {
        $post_type = $this->learndash_post_type('topic');
        $post_type = $post_type['post_type'];
        $topics = get_posts(array(
            'fields'          => 'ids',
            'numberposts' => -1,
            'post_type' => $post_type,
            'post_status' => 'publish'
        ));
        #var_dump($topics);
        foreach ((array) $topics as $topic) {
            $topic_id = $topic;

            $modname = get_post_meta($topic_id, 'modname', true);

            $cmid = get_post_meta($topic_id, 'cpanel_module_id', true);

            if ('bigbluebuttonbn' == $modname) {
                $short = '[bbb_meeting topicid=' . ("$topic_id") . ']';

                $my_post = array(
                    'ID'           => $topic_id,
                    'post_content' =>  $short,
                );
                $my_post = wp_update_post($my_post);
            }
        }
    }


    public function bigbluebuttonbn($baseurl, $token, $topic_id)
    {



        #$topic_id = $post->id;

        $modname = get_post_meta($topic_id, 'modname', true);

        $cmid = get_post_meta($topic_id, 'cpanel_module_id', true);

        if ('bigbluebuttonbn' == $modname) {

            $bigbluebuttonbnid = get_post_meta($topic_id, 'module_instance', true);

            $method = 'GET';
            $body = array(
                'wstoken' => $token,
                'wsfunction' => 'mod_bigbluebuttonbn_meeting_info',
                'moodlewsrestformat' => 'json',
                'bigbluebuttonbnid' => $bigbluebuttonbnid,
            );
            $content = new CPanel_Services;
            $meeting_content = $content->register_moodle_services($baseurl, $method, $body);

            $meetingid = $meeting_content->meetingid;
            $opentime = $meeting_content->openingtime;
            $opentime = gmdate('l Y-m-d H:i:s', $opentime);
            $closingtime = $meeting_content->closingtime;
            $closingtime = gmdate('l Y-m-d H:i:s', $closingtime);
            $meeting['openingtime'] = get_date_from_gmt($opentime, 'Y-m-d g:i A');
            $meeting['closingtime'] = get_date_from_gmt($closingtime, 'Y-m-d g:i A');
            $meeting['statusrunning'] = $meeting_content->statusrunning;
            $meeting['statusclosed'] = $meeting_content->statusclosed;
            $meeting['statusopen'] = $meeting_content->statusopen;
            $meeting['statusmessage'] = $meeting_content->statusmessage;
            $meeting['participantcount'] = $meeting_content->participantcount;
            if (isset($meeting_content->canjoin)){
            $meeting['canjoin'] = $meeting_content->canjoin;
            }
            $meeting['ismoderator'] = $meeting_content->ismoderator;
            #echo $openingtime;




            add_post_meta($topic_id, 'meetingid', $meetingid, true);


            $meetingbody = array(
                'wstoken' => $token,
                'wsfunction' => 'mod_bigbluebuttonbn_get_join_url',
                'moodlewsrestformat' => 'json',
                'cmid' => $cmid,
            );
            // $usecode = __('Join&nbsp;Session', 'coupons_shortcodes_codefish');
            $meeting_url = $content->register_moodle_services($baseurl, $method, $meetingbody);
            $meeting['url'] =isset($meeting_url->join_url);


            $service_url = 'https://lv1.tadreb.live/bigbluebutton/api/';
            $callName = 'getRecordings';
            $queryString = array(
                'meetingID' => $meetingid,
            );
            $parm = 'meetingID=' . urlencode($meetingid);
            $sharedSecret = '06lksGdXVgfGf7hIEso7E4DwHZIPUjwCz41nDvkCI';
            $chuksum = sha1($callName  . $parm . $sharedSecret);

            $arguments = array(
                'method' => 'GET',

            );
            $url = $service_url . $callName . '?' . $parm . '&checksum=' .  $chuksum;
            #echo $url;
            $record_data = wp_remote_get($url, $arguments);

            $record_data = simplexml_load_string(wp_remote_retrieve_body($record_data));
            
            
            $record_data = json_encode($record_data);
            $data = json_decode($record_data, TRUE);
            
            $data = $data['recordings'];
            #var_dump($data);
            #$data = $data['recording'];
            foreach ((array)$data as $recs){
               
                if ( null == $recs['recordID']){
                    foreach ((array)$recs as $rec){
                        $recordid = $rec['recordID'];
                        $meeting['webcam']='https://lv1.tadreb.live/presentation/'.$recordid.'/video/webcams.mp4';
                        $meeting['deskshare'] = 'https://lv1.tadreb.live/presentation/'.$recordid.'/deskshare/deskshare.mp4';
                   
                    }
                }else{
                        $recordid = $recs['recordID'];
                        $meeting['webcam']='https://lv1.tadreb.live/presentation/'.$recordid.'/video/webcams.mp4';
                        $meeting['deskshare'] = 'https://lv1.tadreb.live/presentation/'.$recordid.'/deskshare/deskshare.mp4';
                }
            }
                  
       
        }
        return $meeting;
       
    }
}