<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/**
 * CodeIgniter Security Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Renjith
 * @link		http://codeigniter.com/user_guide/helpers/array_helper.html
 */
// ------------------------------------------------------------------------

/**
 * Element
 *
 * Basic security measures of the entire system
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if (!function_exists('generate_cookie')) {

    function cookie_array($email, $hash, $password) {
        $return = array();
        $return['email'] = md5($email);
        $return['hash'] = $hash;
        $return['password'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $hash . $password);

        return $return;
    }

}

// ---------------------------admin section start---------------------------------------------

if (!function_exists('get_admin_cookie')) {

    function get_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));

        $return = array();
        $return['c_hash'] = $CI->input->cookie('fleet_hash_ad') ? $CI->input->cookie('fleet_hash_ad') : null;
        $return['c_email'] = $CI->input->cookie('fleet_email_ad') ? $CI->input->cookie('fleet_email_ad') : null;
        $return['c_password'] = $CI->input->cookie('fleet_password_ad') ? $CI->input->cookie('fleet_password_ad') : null;
        $return['c_type'] = $CI->input->cookie('fleet_type_ad') ? $CI->input->cookie('fleet_type_ad') : null;
        //print_r($return);
        return $return;
    }

}




if (!function_exists('get_admin_session')) {

    function get_admin_session() {
        $session_data = array(
            'AD_UNIQUEID' => '',
            'AD_NAME' => '',
            'AD_EMAILID' => '',
            'AD_USERID' => '',
            'AD_USERTYPE' => '',
            'AD_USERTYPEID' => '',
            'AD_USERSTATUS' => '',
            'AD_HASREMEMBER' => '',
            'AD_REMEMBER_RAND' => '',
            'AD_CREATED_BY' => 0,
            'AD_LOGINED' => false
        );
        return $session_data;
    }

}


if (!function_exists('password_encry')) {

    function password_encryption($pass) {
        return md5(FLPWD . $pass);
    }

}

if (!function_exists('generate_unique_key')) {

    function generate_unique_key($str = null, $limit = 10) {
        return substr(md5(time() . $str), 1, $limit);
    }

}

if (!function_exists('set_admin_cookie')) {

    function set_admin_cookie($remember_rand, $user_details, $domain_name) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $sec = '86400'; // for 1 day

        $data = cookie_array($user_details["EMAILID"], $remember_rand, $user_details["PASSWORD"]);

        $cookie = array(
            'name' => 'hash_ad',
            'value' => $data['hash'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_e = array(
            'name' => 'email_ad',
            'value' => $data['email'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_p = array(
            'name' => 'password_ad',
            'value' => $data['password'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_t = array(
            'name' => 'type_ad',
            'value' => 'ADMIN',
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_e);
        $CI->input->set_cookie($cookie_p);
        $CI->input->set_cookie($cookie_t);
    }

}



if (!function_exists("delete_admin_cookie")) {

    function delete_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));

        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));

        delete_cookie("hash_ad", $domain_name, '/', 'fleet_');
        delete_cookie("email_ad", $domain_name, '/', 'fleet_');
        delete_cookie("password_ad", $domain_name, '/', 'fleet_');
        delete_cookie("type_ad", $domain_name, '/', 'fleet_');

        return true;
    }

}


if (!function_exists("set_admin_session")) {

    function set_admin_session($has_remember, $remember_rand, $ret) {
        $CI = & get_instance();
        $CI->load->library(array('session'));
        $u_type = "ADMIN";
        $UNIQUEID = $ret["user_unique_id"];
        /** session data array * */
        $session_data = array(
            'AD_UNIQUEID' => $UNIQUEID,
            'AD_EMAILID' => $ret["EMAILID"],
            'AD_NAME' => $ret["FNAME"] . " " . $ret["LNAME"],
            'AD_USERID' => $ret["USERID"],
            'AD_USERTYPE' => $u_type,
            'AD_USERTYPEID' => $ret["USERTYPEID"],
            'AD_USERSTATUS' => $ret["USERSTATUS"],
            'AD_HASREMEMBER' => $has_remember ? true : false,
            'AD_REMEMBER_RAND' => isset($remember_rand[0]) ? $remember_rand : false,
            'AD_LOGINED' => true
        );
        /** session data array * */
        $CI->session->set_userdata($session_data);
    }

}


if (!function_exists("set_logout_cookie")) {

    function set_logout_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));

        $cookie = array(
            'name' => 'adminlogout',
            'value' => true,
            'expire' => 86400, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
    }

}



// ---------------------------admin section end---------------------------------------------
// ---------------------------establishment section start---------------------------------------------

if (!function_exists('get_establishment_cookie')) {

    function get_establishment_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));

        $return = array();
        $return['c_hash'] = $CI->input->cookie('fleet_hash_es') ? $CI->input->cookie('fleet_hash_es') : null;
        $return['c_email'] = $CI->input->cookie('fleet_email_es') ? $CI->input->cookie('fleet_email_es') : null;
        $return['c_password'] = $CI->input->cookie('fleet_password_es') ? $CI->input->cookie('fleet_password_ad') : null;
        $return['c_type'] = $CI->input->cookie('fleet_type_es') ? $CI->input->cookie('fleet_type_es') : null;
        //print_r($return);
        return $return;
    }

}



if (!function_exists('get_establishment_session')) {

    function get_establishment_session() {
        $session_data = array(
            'ES_UNIQUEID' => '',
            'ES_NAME' => '',
            'ES_ESTABLISHMENTID' => '',
            'ES_EMAILID' => '',
            'ES_USERID' => '',
            'ES_USERTYPE' => '',
            'ES_USERTYPECODE' => '',
            'ES_USERTYPEID' => '',
            'ES_USERSTATUS' => '',
            'ES_HASREMEMBER' => '',
            'ES_REMEMBER_RAND' => '',
            'ES_CREATED_BY' => 0,
            'ES_LOGINED' => false,
            'ES_IMAGE' => ''
        );
        return $session_data;
    }

}


if (!function_exists('set_establishment_cookie')) {

    function set_establishment_cookie($remember_rand, $user_details, $domain_name) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $sec = '86400'; // for 1 day

        $data = cookie_array($user_details["EMAILID"], $remember_rand, $user_details["PASSWORD"]);

        $cookie = array(
            'name' => 'hash_es',
            'value' => $data['hash'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_e = array(
            'name' => 'email_es',
            'value' => $data['email'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_p = array(
            'name' => 'password_es',
            'value' => $data['password'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_t = array(
            'name' => 'type_es',
            'value' => 'ESTABLISHMENT',
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_e);
        $CI->input->set_cookie($cookie_p);
        $CI->input->set_cookie($cookie_t);
    }

}


if (!function_exists("set_establishment_session")) {

    function set_establishment_session($has_remember, $remember_rand, $ret) {
        $CI = & get_instance();
        $CI->load->library(array('session'));

        $UNIQUEID = $ret["establishment_unique_id"];
        /** session data array * */
        $session_data = array(
            'ES_UNIQUEID' => $UNIQUEID,
            'ES_NAME' => $ret["NAME"],
            'ES_ESTABLISHMENTID' => $ret["ESTABLISHMENTID"],
            'ES_EMAILID' => $ret["EMAILID"],
            'ES_USERTYPE' => $ret["USERTYPE"],
            'ES_USERTYPECODE' => $ret["USERTYPECODE"],
            'ES_USERTYPEID' => $ret["USERTYPEID"],
            'ES_USERSTATUS' => $ret["USERSTATUS"],
            'ES_HASREMEMBER' => $has_remember ? true : false,
            'ES_REMEMBER_RAND' => isset($remember_rand[0]) ? $remember_rand : false,
            'ES_LOGINED' => true,
            'ES_IMAGE' => $ret["USERIMAGE"]
        );
        /** session data array * */
        $CI->session->set_userdata($session_data);
    }

}


if (!function_exists("delete_establishment_cookie")) {

    function delete_establishment_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        delete_cookie('fleet_hash_es');
        delete_cookie('fleet_email_es');
        delete_cookie('fleet_password_es');
        delete_cookie('fleet_type_es');
    }

}

// ---------------------------establishment section end---------------------------------------------
// ---------------------------call center admin section start---------------------------------------------

if (!function_exists('get_callcenter_admin_cookie')) {

    function get_callcenter_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $return = array();
        $return['c_hash'] = $CI->input->cookie('fleet_hash_cc') ? $CI->input->cookie('fleet_hash_cc') : null;
        $return['c_email'] = $CI->input->cookie('fleet_email_cc') ? $CI->input->cookie('fleet_email_cc') : null;
        $return['c_password'] = $CI->input->cookie('fleet_password_cc') ? $CI->input->cookie('fleet_password_cc') : null;
        $return['c_type'] = $CI->input->cookie('fleet_type_cc') ? $CI->input->cookie('fleet_type_cc') : null;
        //print_r($return);
        return $return;
    }

}

if (!function_exists('get_callcenter_admin_session')) {

    function get_callcenter_admin_session() {
        $session_data = array(
            'CC_UNIQUEID' => '',
            'CC_NAME' => '',
            'CC_EMAILID' => '',
            'CC_USERID' => '',
            'CC_USERTYPE' => '',
            'CC_USERTYPECODE' => '',
            'CC_USERTYPEID' => '',
            'CC_USERSTATUS' => '',
            'CC_HASREMEMBER' => '',
            'CC_REMEMBER_RAND' => '',
            'CC_CREATED_BY' => 0,
            'CC_LOGINED' => false,
            'CC_IMAGE' => '',
            'CC_VEHICLE_LOCATIONID' => ''
        );

        return $session_data;
    }

}

if (!function_exists('set_callcenter_admin_cookie')) {

    function set_callcenter_admin_cookie($remember_rand, $user_details, $domain_name) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $sec = '86400'; // for 1 day

        $data = cookie_array($user_details["EMAILID"], $remember_rand, $user_details["PASSWORD"]);

        $cookie = array(
            'name' => 'hash_cc',
            'value' => $data['hash'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_e = array(
            'name' => 'email_cc',
            'value' => $data['email'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_p = array(
            'name' => 'password_cc',
            'value' => $data['password'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_t = array(
            'name' => 'type_cc',
            'value' => 'ADMIN',
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_e);
        $CI->input->set_cookie($cookie_p);
        $CI->input->set_cookie($cookie_t);
    }

}

if (!function_exists("delete_callcenter_admin_cookie")) {

    function delete_callcenter_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        delete_cookie("hash_cc", $domain_name, '/', 'fleet_');
        delete_cookie("email_cc", $domain_name, '/', 'fleet_');
        delete_cookie("password_cc", $domain_name, '/', 'fleet_');
        delete_cookie("type_cc", $domain_name, '/', 'fleet_');
        return true;
    }

}

if (!function_exists("set_callcenter_admin_session")) {

    function set_callcenter_admin_session($has_remember, $remember_rand, $ret) {
        $CI = & get_instance();
        $CI->load->library(array('session'));
        $u_type = "CALLCENTERADMIN";
        $UNIQUEID = $ret["establishment_unique_id"];
        /** session data array * */
        $session_data = array(
            'CC_UNIQUEID'       => $UNIQUEID,
            'CC_NAME'           => $ret["NAME"] . " " . $ret["LNAME"],
            'CC_USERID'         => $ret["ESTABLISHMENTID"],
            'CC_EMAILID'        => $ret["EMAILID"],
            'CC_USERTYPE'       => $ret["USERTYPE"],
            'CC_USERTYPECODE'   => $ret["USERTYPECODE"],
            'CC_USERTYPEID'     => $ret["USERTYPEID"],
            'CC_USERSTATUS'     => $ret["USERSTATUS"],
            'CC_HASREMEMBER'    => $has_remember ? true : false,
            'CC_REMEMBER_RAND'  => isset($remember_rand[0]) ? $remember_rand : false,
            'CC_LOGINED'        => true,
            'CC_IMAGE'          => $ret["USERIMAGE"],
            'CC_VEHICLE_LOCATIONID'     => DEFAULT_VEHICLE_LOCATIONID
        );

        /** session data array * */
        $CI->session->set_userdata($session_data);
    }

}

// ---------------------------callcenter admin section end---------------------------------------------

// ---------------------------callcenter user section start
if (!function_exists('get_callcenter_user_cookie')) {

    function get_callcenter_user_cookie($usertypecode) {
        $usertypecode = strtolower($usertypecode);
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $return = array();
        $return['c_hash'] = $CI->input->cookie('fleet_hash_'.$usertypecode.'') ? $CI->input->cookie('fleet_hash_'.$usertypecode.'') : null;
        $return['c_email'] = $CI->input->cookie('fleet_hash_'.$usertypecode.'') ? $CI->input->cookie('fleet_hash_'.$usertypecode.'') : null;
        $return['c_password'] = $CI->input->cookie('fleet_hash_'.$usertypecode.'') ? $CI->input->cookie('fleet_hash_'.$usertypecode.'') : null;
        $return['c_type'] = $CI->input->cookie('fleet_hash_'.$usertypecode.'') ? $CI->input->cookie('fleet_hash_'.$usertypecode.'') : null;
        //print_r($return);
        return $return;
    }

}
if (!function_exists('get_callcenter_user_session')) {

    function get_callcenter_user_session($usertypecode) {
        $session_data = array(
            ''.$usertypecode.'_UNIQUEID' => '',
            ''.$usertypecode.'_NAME' => '',
            ''.$usertypecode.'_EMAILID' => '',
            ''.$usertypecode.'_USERID' => '',
            ''.$usertypecode.'_USERTYPE' => '',
            ''.$usertypecode.'_USERTYPECODE' => '',
            ''.$usertypecode.'_USERTYPEID' => '',
            ''.$usertypecode.'_USERSTATUS' => '',
            ''.$usertypecode.'_HASREMEMBER' => '',
            ''.$usertypecode.'_REMEMBER_RAND' => '',
            ''.$usertypecode.'_CREATED_BY' => 0,
            ''.$usertypecode.'_LOGINED' => false,
            ''.$usertypecode.'_IMAGE' => ''
        );

        return $session_data;
    }

}
if (!function_exists('set_callcenter_user_cookie')) {

    function set_callcenter_user_cookie($remember_rand, $user_details, $domain_name,$usertypecode,$usertype) {
       
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $sec = '86400'; // for 1 day

        $data = cookie_array($user_details["EMAILID"], $remember_rand, $user_details["PASSWORD"]);
        $usertypecode = strtolower($usertypecode);
        $cookie = array(
            'name' => 'hash_'.$usertypecode.'',
            'value' => $data['hash'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_e = array(
            'name' => 'email_'.$usertypecode.'',
            'value' => $data['email'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_p = array(
            'name' => 'password_'.$usertypecode.'',
            'value' => $data['password'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_t = array(
            'name' => 'type_'.$usertypecode.'',
            'value' => $usertype,
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_e);
        $CI->input->set_cookie($cookie_p);
        $CI->input->set_cookie($cookie_t);
       
    }

}
if (!function_exists("delete_callcenter_user_cookie")) {

    function delete_callcenter_user_cookie($usertypecode) {
        $usertypecode = strtolower($usertypecode);
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        delete_cookie("hash_".$usertypecode."", $domain_name, '/', 'fleet_');
        delete_cookie("email_".$usertypecode."", $domain_name, '/', 'fleet_');
        delete_cookie("password_".$usertypecode."", $domain_name, '/', 'fleet_');
        delete_cookie("type_".$usertypecode."", $domain_name, '/', 'fleet_');
        return true;
    }

}
if (!function_exists("set_callcenter_user_session")) {

    function set_callcenter_user_session($has_remember, $remember_rand, $ret,$usertypecode) {
        $CI = & get_instance();
        $CI->load->library(array('session'));
        
        $UNIQUEID = $ret["establishment_unique_id"];
        /** session data array * */
        $session_data = array(
            ''.$usertypecode.'_UNIQUEID' => $UNIQUEID,
            ''.$usertypecode.'_NAME' => $ret["NAME"] . " " . $ret["LNAME"],
            ''.$usertypecode.'_USERID' => $ret["ESTABLISHMENTID"],
            ''.$usertypecode.'_EMAILID' => $ret["EMAILID"],
            ''.$usertypecode.'_USERTYPE' => $ret["USERTYPE"],
            ''.$usertypecode.'_USERTYPECODE' => $ret["USERTYPECODE"],
            ''.$usertypecode.'_USERTYPEID' => $ret["USERTYPEID"],
            ''.$usertypecode.'_USERSTATUS' => $ret["USERSTATUS"],
            ''.$usertypecode.'_HASREMEMBER' => $has_remember ? true : false,
            ''.$usertypecode.'_REMEMBER_RAND' => isset($remember_rand[0]) ? $remember_rand : false,
            ''.$usertypecode.'_LOGINED' => true,
            ''.$usertypecode.'_IMAGE' => $ret["USERIMAGE"]
        );

        /** session data array * */
        $CI->session->set_userdata($session_data);
    }

}
if (!function_exists("delete_callcenter_admin_cookie")) {

    function delete_callcenter_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        delete_cookie("hash_cc", $domain_name, '/', 'fleet_');
        delete_cookie("email_cc", $domain_name, '/', 'fleet_');
        delete_cookie("password_cc", $domain_name, '/', 'fleet_');
        delete_cookie("type_cc", $domain_name, '/', 'fleet_');
        return true;
    }
}
if (!function_exists("makecallcenter_session_data")) {
    function makecallcenter_session_data($userdata,$usertypecode) {
        $session_data = array();
        $session_data['CC_UNIQUEID']    = $userdata[''.$usertypecode.'_UNIQUEID'];
        $session_data['CC_NAME']        = $userdata[''.$usertypecode.'_NAME'];
        $session_data['CC_USERTYPE']    = $userdata[''.$usertypecode.'_USERTYPE'];
        $session_data['CC_IMAGE']       = $userdata[''.$usertypecode.'_IMAGE'];
        $session_data['CC_USERTYPEID']  = $userdata[''.$usertypecode.'_USERTYPEID'];
        return $session_data;
    }

}


// ---------------------------callcenter user section end

// ---------------------------controlcenter admin section start---------------------------------------------

if (!function_exists('get_controlcenter_admin_cookie')) {

    function get_controlcenter_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $return = array();
        $return['c_hash'] = $CI->input->cookie('fleet_hash_cl') ? $CI->input->cookie('fleet_hash_cl') : null;
        $return['c_email'] = $CI->input->cookie('fleet_email_cl') ? $CI->input->cookie('fleet_email_cl') : null;
        $return['c_password'] = $CI->input->cookie('fleet_password_cl') ? $CI->input->cookie('fleet_password_cl') : null;
        $return['c_type'] = $CI->input->cookie('fleet_type_cl') ? $CI->input->cookie('fleet_type_cl') : null;
        //print_r($return);
        return $return;
    }

}

if (!function_exists('get_controlcenter_admin_session')) {

    function get_controlcenter_admin_session() {
        $session_data = array(
            'CL_UNIQUEID' => '',
            'CL_NAME' => '',
            'CL_EMAILID' => '',
            'CL_USERID' => '',
            'CL_USERTYPE' => '',
            'CL_USERTYPECODE' => '',
            'CL_USERTYPEID' => '',
            'CL_USERSTATUS' => '',
            'CL_HASREMEMBER' => '',
            'CL_REMEMBER_RAND' => '',
            'CL_CREATED_BY' => 0,
            'CL_LOGINED' => false,
            'CL_VEHICLE_LOCATIONID'     => ''
        );

        return $session_data;
    }

}

if (!function_exists('set_controlcenter_admin_cookie')) {

    function set_controlcenter_admin_cookie($remember_rand, $user_details, $domain_name) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $sec = '86400'; // for 1 day

        $data = cookie_array($user_details["EMAILID"], $remember_rand, $user_details["PASSWORD"]);

        $cookie = array(
            'name' => 'hash_cl',
            'value' => $data['hash'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_e = array(
            'name' => 'email_cl',
            'value' => $data['email'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_p = array(
            'name' => 'password_cl',
            'value' => $data['password'],
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_t = array(
            'name' => 'type_cl',
            'value' => 'ADMIN',
            'expire' => $sec, // 86400 sec for 1 day
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );

        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_e);
        $CI->input->set_cookie($cookie_p);
        $CI->input->set_cookie($cookie_t);
    }

}

if (!function_exists("delete_controlcenter_admin_cookie")) {

    function delete_controlcenter_admin_cookie() {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        delete_cookie("hash_cl", $domain_name, '/', 'fleet_');
        delete_cookie("email_cl", $domain_name, '/', 'fleet_');
        delete_cookie("password_cl", $domain_name, '/', 'fleet_');
        delete_cookie("type_cl", $domain_name, '/', 'fleet_');
        return true;
    }

}

if (!function_exists("set_controlcenter_admin_session")) {

    function set_controlcenter_admin_session($has_remember, $remember_rand, $ret) {
        $CI = & get_instance();
        $CI->load->library(array('session'));
        $u_type = "CONTROLCENTERADMIN";
        $UNIQUEID = $ret["establishment_unique_id"];
        /** session data array * */
        $session_data = array(
            'CL_UNIQUEID' => $UNIQUEID,
            'CL_NAME' => $ret["NAME"] . " " . $ret["LNAME"],
            'CL_USERID' => $ret["ESTABLISHMENTID"],
            'CL_EMAILID' => $ret["EMAILID"],
            'CL_USERTYPE' => $ret["USERTYPE"],
            'CL_USERTYPECODE' => $ret["USERTYPECODE"],
            'CL_USERTYPEID' => $ret["USERTYPEID"],
            'CL_USERSTATUS' => $ret["USERSTATUS"],
            'CL_HASREMEMBER' => $has_remember ? true : false,
            'CL_REMEMBER_RAND' => isset($remember_rand[0]) ? $remember_rand : false,
            'CL_LOGINED' => true,
            'CL_VEHICLE_LOCATIONID'     => DEFAULT_VEHICLE_LOCATIONID
        );

        /** session data array * */
        $CI->session->set_userdata($session_data);
    }

}

// ---------------------------controlcenter admin section end---------------------------------------------
//----------------------------aajax redirection--------------------------

if (!function_exists("ajax_redirect")) {

    function ajax_redirect($path, $type = 'admin') {
        $ret = array("logout" => 1, "url" => $path, "type" => $type);
        print json_encode($ret);
        exit;
    }

}


//----------------------------notification cookie--------------------------

if (!function_exists("notify_success")) {

    function notify_success($msg) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        $cookie = array(
            'name' => 'success',
            'value' => 1,
            'expire' => 3600, // one minute
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_s = array(
            'name' => 'success_msg',
            'value' => $msg,
            'expire' => 3600, // one minute
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_s);
        return true;
    }

}

if (!function_exists("notify_error")) {

    function notify_error($msg) {
        $CI = & get_instance();
        $CI->load->helper(array('cookie'));
        $domain_name = preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/", "$1", $CI->config->slash_item('base_url'));
        $cookie = array(
            'name' => 'error',
            'value' => 1,
            'expire' => 3600, // one minute
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $cookie_s = array(
            'name' => 'error_msg',
            'value' => $msg,
            'expire' => 3600, // one minute
            'domain' => $domain_name,
            'prefix' => 'fleet_'
        );
        $CI->input->set_cookie($cookie);
        $CI->input->set_cookie($cookie_s);
        return true;
    }

}

if (!function_exists("ajax_call_notify")) {

    function ajax_call_notify() {
        echo '
            <script>
            eval(alert("hi"));
if ( $.cookie("fleet_success") ){
            $.notify($.cookie("fleet_success_msg"), "success");
            $.removeCookie("fleet_success",{ path: "/" }); $.removeCookie("fleet_success_msg",{ path: "/" });
        }
        
        if ( $.cookie("fleet_error") ){
            $.notify($.cookie("fleet_error_msg"), "error");
            $.removeCookie("fleet_error",{ path: "/" }); $.removeCookie("fleet_error_msg",{ path: "/" });
        }
        </script>
';
    }

}

if (!function_exists("array_indexed")) {

    function array_indexed($marray, $key) { 
        if (empty($marray))
            return FALSE;
        $new_array = array();
        foreach ($marray as $value) {
            $new_array[$value[$key]] = $value;
        }

        return $new_array;
    }

}

if (!function_exists("time_gmt")) {

    function time_gmt($gmttime, $timezoneRequired = 'Asia/Calcutta') {
        
        $CI = & get_instance();
        $CI->load->helper(array('date'));
        $now = strtotime($gmttime);
        $toTz="Asia/Kolkata";   
        $fromTz = 'UTC';
        // timezone by php friendly values
        $date = new DateTime($gmttime, new DateTimeZone($fromTz));
        $date->setTimezone(new DateTimeZone($toTz));
        if( LOCAL_TIMEZONE ){
          $time = $gmttime;  
        }
        else{
            $time= $date->format('Y-m-d H:i:s');
        }
        
        return strtotime($time);
	
        
        //$now = time();
        $timestamp = local_to_gmt($now);
       
        print date("d-m-Y h:i:s A",$timestamp);exit;
        print $timestamp;
        exit;
        $timezone = 'UP45';
        
        return gmt_to_local($timestamp);
        return date("d-m-Y h:i:s A");
    }

}

if (!function_exists("time_convert")) {

    function time_convert($gmttime, $timezoneRequired = 'Asia/Calcutta') {
        $CI = & get_instance();
        $CI->load->helper(array('date'));
        $now = strtotime($gmttime);
        //$now = time();
        $timestamp = local_to_gmt($now);
        $timezone = 'UP55';
        $daylight_saving = FALSE;
        return gmt_to_local($timestamp, $timezone, $daylight_saving);
    }

}
if (!function_exists("arraysort_by_value")) {

    function arraysort_by_value(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
    }

}

/**
 * set post values
 *
 * @param $field (feild name)
 * @param $default (default value)
 * @return set vale
 */
if (!function_exists('set_post_value')) {

    function set_post_value($field = '', $default = '') {
        if (!isset($_POST[$field])) {
            return $default;
        }

        return $_POST[$field];
    }

}

/**
 * set post check box values
 *
 * @param  $field
 * @param  $value
 * @param  $default
 * @return true or false
 */
if (!function_exists('set_post_check_value')) {

    function set_post_check_value($field = '', $value = '', $default = '') {
        if (!isset($_POST[$field])) {
            if (is_array($default) and in_array($value, $default)) {
                return TRUE;
            }
            if ($default === $value) {
                return TRUE;
            }
            return FALSE;
        }

        if (is_array($_POST[$field]) and in_array($value, $_POST[$field])) {
            return TRUE;
        }
        if ($_POST[$field] === $value) {
            return TRUE;
        }
        return FALSE;

        //return $_POST[$field];
    }

}


if (!function_exists('usortCallback')) {

    function usortCallback($a, $b, $key, $order) {
         
        $t1 = $a[$key];
        $t2 = $b[$key];

        
        if (is_string($t1) && is_string($t2)) {
            if ($order === 'asc') {
                return strcmp($t1, $t2);
            } else {
                return strcmp($t2, $t1);
            }
        } elseif ( ( is_int($t1) || is_float($t1) ) && ( is_int($t2) || is_float($t2) ) ) {
            if ($order === 'asc') {
                return $t1 - $t2;
            } else {
                return $t2 - $t1;
            }
        }else {
            trigger_error('Invalid type in photoSort!', E_WARNING);
        }
       
    }

}

/**
 * multidimensional array sorting function
 *
 * @param  $array
 * @param  $key
 * @param  $order
 * @return sorted array
 */
if (!function_exists('array_multi_sort')) {

    function array_multi_sort(array &$myarray, $key, $order) {
        if ($order !== 'desc' && $order !== 'asc') {
            return false;
        }

        usort($myarray, create_function('$a, $b', 'return usortCallback($a, $b, "' . $key . '", "' . $order . '");'));
    }

}

if (!function_exists('generateUniqueKey')) {
    function generateUniqueKey($table, $feild, $count = 8) {
        $CI = & get_instance();
        do {
            //$ukey = substr(strtolower(md5(microtime() . rand())), 0, $count);
            $ukey = substr(number_format(time() * rand(),0,'',''),0,$count);
            $query = $CI->db->query("select * from $table where $feild = '" . $ukey . "' limit 1");
            
        } while ( $query->num_rows() );

        return $ukey;
    }
}

if(!function_exists('weekdays_from_date')){
    function weekdays_from_date($date){
        if( ! $date )$date = date('Y/m/d');
        $d = date('N', strtotime($date));// here N means ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
        $week = array();
        for ($i = 1; $i < $d; ++$i) {
            $dateTime = new DateTime($date);
            for ($j = 0; $j < $i; ++$j) {
                $dateTime->modify('-1 day');
            }
            $week[] = $dateTime;
        }
        $week[] = new DateTime($date);
        for ($i = $d + 1; $i <= 7; ++$i) {
            $dateTime = new DateTime($date);
            for ($j = 0; $j < $i - $d; ++$j) {
                $dateTime->modify('+1 day');
            }
            $week[] = $dateTime;
        }
        sort($week);
        $return = array();
        foreach ($week as $day) {
            //echo $day->format('Y-m-d'), '<br />';
            $return[] = $day->format('Y-m-d');
        }
        
        return $return;
    }
}


if(!function_exists('sevendays_from_date')){
    function sevendays_from_date($date,$type = 'prev'){
        $timestamp = strtotime($date);
        for ($i = 0 ; $i < 7 ; $i++) {
            $return[] = date('Y-m-d', $timestamp);
            if( $type == 'prev' ){
                $timestamp -= 24 * 3600;
            }
            else{
                $timestamp += 24 * 3600;
            }
            
        }
        sort($return);
        return $return;
    }
}

if(!function_exists('validate_date')){
function validate_date($date)
{
    return true;
         $dateTime = new DateTime();
    $d = $dateTime->createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}
}


