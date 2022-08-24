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
 * Authentication Plugin: Email Authentication
 *
 * @author Martin Dougiamas
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package auth_email
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Email authentication plugin.
 */
class auth_plugin_email extends auth_plugin_base {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'email';
        $this->config = get_config('auth_email');
    }

    /**
     * Old syntax of class constructor. Deprecated in PHP7.
     *
     * @deprecated since Moodle 3.1
     */
    public function auth_plugin_email() {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct();
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG, $DB;
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return validate_internal_user_password($user, $password);
        }
        return false;
    }

    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object  (with system magic quotes)
     * @param  string  $newpassword Plaintext password (with system magic quotes)
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    function can_signup() {
        return true;
    }

    /**
     * Sign up a new user ready for confirmation.
     * Password is passed in plaintext.
     *
     * @param object $user new user object
     * @param boolean $notify print notice with link and terminate
     */
    function user_signup($user, $notify=true) {
        // Standard signup, without custom confirmatinurl.
        return $this->user_signup_with_confirmation($user, $notify);
    }

    /**
     * Sign up a new user ready for confirmation.
     *
     * Password is passed in plaintext.
     * A custom confirmationurl could be used.
     *
     * @param object $user new user object
     * @param boolean $notify print notice with link and terminate
     * @param string $confirmationurl user confirmation URL
     * @return boolean true if everything well ok and $notify is set to true
     * @throws moodle_exception
     * @since Moodle 3.2
     */
    public function user_signup_with_confirmation($user, $notify=true, $confirmationurl = null) {
        global $CFG, $DB, $SESSION;
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');
        

        $plainpassword = $user->password;
        $user->password = hash_internal_user_password($user->password);
        if (empty($user->calendartype)) {
            $user->calendartype = $CFG->calendartype;
        }

        $user->id = user_create_user($user, false, false);

        user_add_password_history($user->id, $plainpassword);

        // Save any custom profile field information.
        profile_save_data($user);
        
        profile_load_custom_fields($user); 
        
        // var_dump($user);
        // echo '<br><br>'.$user->profile_field_type ;
        // die();
        
        if($user->profile_field_type == 'Organization'){
            
            require_once($CFG->dirroot.'/local/iomad/lib/company.php');
            require_once($CFG->dirroot.'/custom-php/godaddy_api.php');
            require_once($CFG->dirroot.'/custom-php/cpanel_api.php');
            require_once($CFG->dirroot.'/custom-php/generic_helper.php');
            
            // CREATE COMPANY 
            
            // Set up a profiles field category for this company.
            $catdata = new stdclass();
            $catdata->sortorder = $DB->count_records('user_info_category') + 1;
            $catdata->name = $user->profile_field_short_name;
            
            $data = new stdclass();
            $data->userid = $user->id;
            $data->profileid = $DB->insert_record('user_info_category', $catdata);
    
            // Deal with leading/trailing spaces
            $data->name = trim($user->profile_field_long_name);
            $data->shortname = trim($user->profile_field_short_name);
            $data->hostname = trim($user->profile_field_hostname);
            $data->country = trim($user->country);
            $data->city = trim($user->city);
        
            
            $companyid = $DB->insert_record('company', $data);
            $company = new company($companyid);
    
            $eventother = array('companyid' => $companyid);
    
            $event = \block_iomad_company_admin\event\company_created::create(array('context' => context_system::instance(),
                                                                                    'userid' => $USER->id,
                                                                                    'objectid' => $companyid,
                                                                                    'other' => $eventother));
            $event->trigger();
    
            // Set up default department.
            company::initialise_departments($companyid);
            $data->id = $companyid;
    
            // Set up course category for company.
            $coursecat = new stdclass();
            $coursecat->name = $data->name;
            $coursecat->sortorder = 999;
            $coursecat->id = $DB->insert_record('course_categories', $coursecat);
            $coursecat->context = context_coursecat::instance($coursecat->id);
            $categorycontext = $coursecat->context;
            $categorycontext->mark_dirty();
            $DB->update_record('course_categories', $coursecat);
            fix_course_sortorder();
            $companydetails = $DB->get_record('company', array('id' => $companyid));
            $companydetails->category = $coursecat->id;
            $DB->update_record('company', $companydetails);
            $redirectmessage = get_string('companycreatedok', 'block_iomad_company_admin');
    
            // Deal with any assigned templates.
            // if (!empty($data->templates)) {
            //     $company->assign_role_templates($data->templates);
            // }
    
            // Deal with certificate info.
            // $certificateinforec = array('companyid' => $companyid,
            //                             'uselogo' => $data->uselogo,
            //                             'usesignature' => $data->usesignature,
            //                             'useborder' => $data->useborder,
            //                             'usewatermark' => $data->usewatermark,
            //                             'showgrade' => $data->showgrade);
            // $DB->insert_record('companycertificate', $certificateinforec);
            
            // END CREATE COMPANY
            
            // $company = new company($user->companyid);
            
            //CHECK IF USER BELONGS TO COMPANY
            
            // $context = context_system::instance();
            // // Set the companyid
            // $searchCompanyid = iomad::get_my_companyid($context);
            // // Get the list of users.
            // $myusers = $company->get_my_users($searchCompanyid);
            
            // // If the user is in the list, return true.
            // if (!empty($myusers[$userid->id])) {
            //     var_dump($searchCompanyid);
            //     die();
            //     //return true;
            // }
            
            // END CHECK IF USER BELONGS TO COMPANY
            
    
            // Assign them to any department.
            if (!empty($user->departmentid)) {
                $company->assign_user_to_department( $user->departmentid, $user->id,1);
            }
    
            if ($CFG->local_iomad_signup_autoenrol) {
                $company->autoenrol($user);
            }
            
            $dnsHostname = $user->profile_field_hostname;
            
            // assign the user to the company.
            
            
            $company->assign_user_to_company($user->id);
            
            $DB->execute("UPDATE {company_users} SET managertype = 1 WHERE userid = '{$user->id}' AND companyid= '{$companyid}'");
            
            
            $companyUsers = $DB->get_record('company_users', array('userid' => $user->id, 'companyid'=> $companyid));
            
            // var_dump($company->get_managertypes());
            // die();
            company::upsert_company_user($user->id, $companyid, $companyUsers->departmentid, 1, false, false, true);
                
            // try{
            //     $departmentData = new stdClass();
            //     $departmentData->companyid = $companyid;
            //     $departmentData->userid = $user->id;
            //     $departmentData->managertype = 1;
            //     $departmentData->departmentid = !empty($parentnode) ? $parentnode->id : 0;
            //     $departmentData->suspended = 0;
            //     $departmentData->educator = 0;
            //     // $success = $DB->insert_record('company_users', $departmentData);
                
             
            // }catch(Exception $e){
            //       // the potential error is stored in $e, just do whatever you want with it. 
            //      var_dump($e);
            //      die();
        
            // }
            
            // Test if string contains the word 
            if(strpos($user->profile_field_hostname, $_SERVER["SERVER_NAME"]) !== false){
                $dnsHostname = substr($user->profile_field_hostname, 0, strpos($user->profile_field_hostname, '.'));
            }
            
            updateDns("A", $dnsHostname,$_SERVER["SERVER_ADDR"],strtolower($_SERVER["SERVER_NAME"]));
             
             createSubDomain($user->profile_field_hostname,strtolower($_SERVER["SERVER_NAME"]),getServerAddresWithoutHome(),$_SERVER["SERVER_ADDR"]);
            
        }else{
            
            // IOMAD.
            if (!empty($user->companyid)) {
                require_once($CFG->dirroot.'/local/iomad/lib/company.php');
                $company = new company($user->companyid);
    
                // assign the user to the company.
                $company->assign_user_to_company($user->id);
    
                // Assign them to any department.
                if (!empty($user->departmentid)) {
                    $company->assign_user_to_department($user->departmentid, $user->id);
                }
    
                if ($CFG->local_iomad_signup_autoenrol) {
                    $company->autoenrol($user);
                }
            }
            
        }

        // Save wantsurl against user's profile, so we can return them there upon confirmation.
        if (!empty($SESSION->wantsurl)) {
            set_user_preference('auth_email_wantsurl', $SESSION->wantsurl, $user);
        }

        // Trigger event.
        \core\event\user_created::create_from_userid($user->id)->trigger();

        if (! send_confirmation_email($user)) {
            print_error('auth_emailnoemail','auth_email');
        }

        if ($notify) {
            global $CFG, $PAGE, $OUTPUT;
            $emailconfirm = get_string('emailconfirm');
            $PAGE->navbar->add($emailconfirm);
            $PAGE->set_title($emailconfirm);
            $PAGE->set_heading($PAGE->course->fullname);
            echo $OUTPUT->header();
            notice(get_string('emailconfirmsent', '', $user->email), "$CFG->wwwroot/index.php");
        } else {
            return true;
        }
    }

    /**
     * Returns true if plugin allows confirming of new users.
     *
     * @return bool
     */
    function can_confirm() {
        return true;
    }

    /**
     * Confirm the new user as registered.
     *
     * @param string $username
     * @param string $confirmsecret
     */
    function user_confirm($username, $confirmsecret) {
        global $DB, $SESSION;
        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->auth != $this->authtype) {
                return AUTH_CONFIRM_ERROR;

            } else if ($user->secret === $confirmsecret && $user->confirmed) {
                return AUTH_CONFIRM_ALREADY;

            } else if ($user->secret === $confirmsecret) {   // They have provided the secret key to get in
                $DB->set_field("user", "confirmed", 1, array("id"=>$user->id));

                if ($wantsurl = get_user_preferences('auth_email_wantsurl', false, $user)) {
                    // Ensure user gets returned to page they were trying to access before signing up.
                    $SESSION->wantsurl = $wantsurl;
                    unset_user_preference('auth_email_wantsurl', $user);
                }

                return AUTH_CONFIRM_OK;
            }
        } else {
            return AUTH_CONFIRM_ERROR;
        }
    }

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null; // use default internal method
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return true;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }

    /**
     * Returns whether or not the captcha element is enabled.
     * @return bool
     */
    function is_captcha_enabled() {
        return get_config("auth_{$this->authtype}", 'recaptcha');
    }

}
