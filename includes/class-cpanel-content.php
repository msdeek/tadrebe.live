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
                $value = $course->id;
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
                        'post_title' => $fullname,
                        'post_type' =>  $post_type,
                    ));
                    add_post_meta($post_id, $key, $value, true);
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
        $courses = get_posts([
            'post_type' => $post_type
        ]);
        if (0 != $token) {
            foreach ((array)$courses as $course) {
                $course = $course->ID;
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
                    $lesson_value = $lesson->id;
                    $lesson_fullname = $lesson->name;
                    $lesson_section = $lesson->section;
                    $visible = $lesson->visible;
                    $modules = $lesson->modules;
                    if ($visible == '1') {
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
                        ));
                        add_post_meta($lesson_id, $lesson_key, $lesson_value, true);
                        learndash_update_setting($lesson_id, 'course', $course, true);
                    } else {
                        $lesson_id = wp_update_post(array(
                            'ID' => $lesson_id,
                            'post_title' => $lesson_fullname,
                            'post_status' =>  $post_status,
                            'menu_order' => $lesson_section
                        ));
                    }
                


                    foreach ((array) $modules as $module) {

                        $module_post_type = $this->learndash_post_type('topic');
                        $module_key = $module_post_type['key'];
                        $module_post_type = $module_post_type['post_type'];

                        $module_value = $module->id;






                        $module_fullname = $module->name;
                        $modname = $module->modname;
                        $module_instance = $module->instance;
                        $module_contextid = $module->contextid;


                        if ($visible == '1') {
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
                            ));
                            add_post_meta($topic_id, $module_key, $module_value, true);
                            learndash_update_setting($topic_id, 'course', $course, true);
                            learndash_update_setting($topic_id, 'lesson', $lesson_id, true);

                            add_post_meta($topic_id, 'modname', $modname, true);
                            add_post_meta($topic_id, 'module_instance', $module_instance, true);
                            add_post_meta($topic_id, 'module_contextid', $module_contextid, true);
                        }else{             
                                $topic_id = wp_update_post(array(
                                    'ID' => $module_id,
                                    'post_title' => $module_fullname,
                                    'post_status' =>  $post_status,
                                ));
                                /**if ('bigbluebuttonbn' == $modname) {
                                    
                                    $bigbluebuttonbnid = get_post_meta($topic_id, 'module_instance', true);
                                    
                                    $method = 'GET';
                                    $body = array(
                                        'wstoken' => $token,
                                        'wsfunction' => 'mod_bigbluebuttonbn_meeting_info',
                                        'moodlewsrestformat' => 'json',
                                        'bigbluebuttonbnid' => $bigbluebuttonbnid,
                                    );
                                    $content = new CPanel_Services; 
                                    $content = $content->register_moodle_services($baseurl, $method, $body);
                                    $meetingid = $content->meetingid;
                                        echo $meetingid;
                                    
                                    
                                    
                                    
                                    add_post_meta($topic_id, 'meetingid', $meetingid, true);

                                    
                                    
                                    
                                    $my_post = array(
                                        'ID' => $module_id,
                                        'post_content' => '<h1>'. $meetingid .'</h1>',
                                    );
                                    wp_update_post($my_post);
                                }*/
                           
                        }
                    }
                }
            }
        }
    }


}
