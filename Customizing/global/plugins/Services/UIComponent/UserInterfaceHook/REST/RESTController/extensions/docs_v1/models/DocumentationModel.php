<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer, T.Hufschmidt <(schaefer|hufschmidt)@hrz.uni-marburg.de>
 * Since 2014
 */
namespace RESTController\extensions\docs_v1;

// This allows us to use shortcuts instead of full quantifier
use \RESTController\libs as Libs;


class DocumentationModel extends Libs\RESTModel
{

    public $docs = array();

    function __construct() {
        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // admin_v1
        $this->docs['get/v1/admin/files/:id'] = array(
            'route'         => '/v1/admin/files/:id',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Admin Route. Downloads a file with a given id (ref_id). If parameter is set to
                                true then only descriptions about a file in json format are provided.',
            'parameters'     => '{"meta_data":"true"}'
        );

        $this->docs['get/v1/admin/describe/:id'] = array(
            'route'         => '/v1/admin/describe/:id',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns a description of an object or user specified by its obj_id, ref_id, usr_id or file_id. Supported types: obj_id, ref_id, usr_id and file_id.',
            'parameters'    => '{"id_type":"ref_id"}'
        );

        $this->docs['get/v1/admin//desktop/overview/:id'] = array(
            'route'         => '/v1/admin//desktop/overview/:id',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Retrieves all items from the personal desktop of a user specified by its id.',
            'parameters'    => '{}'
        );

        $this->docs['delete/v1/admin//desktop/overview/:id'] = array(
            'route'         => '/v1/admin//desktop/overview/:id',
            'verb'          => 'DELETE',
            'group'         => '/v1/admin',
            'description'   => 'Deletes an item specified by ref_id from the personal desktop of the user specified by $id.',
            'parameters'    => '{"ref_id":"ID"}'
        );

        $this->docs['get/v1/admin/reporting/active_sessions'] = array(
            'route'         => '/v1/admin/reporting/active_sessions',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns a list of active user sessions.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/reporting/session_stats'] = array(
            'route'         => '/v1/admin/reporting/session_stats',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns statistics about current user sessions.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/reporting/session_stats_daily'] = array(
            'route'         => '/v1/admin/reporting/session_stats_daily',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns statistics about user sessions within a 24-h time frame.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/reporting/session_stats_hourly'] = array(
            'route'         => '/v1/admin/reporting/session_stats_daily',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns statistics about user sessions within a 1-h time frame.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/reporting/object_stats'] = array(
            'route'         => '/v1/admin/reporting/session_stats_daily',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Provides a snapshot of the total access counts of repository object types.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/reporting/repository_stats'] = array(
            'route'         => '/v1/admin/reporting/repository_stats',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns the access statistics on all ILIAS repository objects within a 24-h time frame. In addition to the access numbers the route also provides information about the objects, such as title, type, location within the repository hierarchy',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/testquestion/:question_id'] = array(
            'route'         => '/v1/admin/testquestion/:question_id',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns a (json) representation of a test question given its question_id.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/workspaces'] = array(
            'route'         => '/v1/admin/workspaces',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Provides an overview of workspaces of a limited amount of users.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/admin/workspaces/:user_id'] = array(
            'route'         => '/v1/admin/workspaces/:user_id',
            'verb'          => 'GET',
            'group'         => '/v1/admin',
            'description'   => 'Returns the content of the workspace from a user specified by her/his user id.',
            'parameters'    => '{}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // bibliography_v1
        $this->docs['get/v1/biblio/:ref_id'] = array(
            'route'         => '/v1/biblio/:ref_id',
            'verb'          => 'GET',
            'group'         => '/v1/biblio',
            'description'   => 'Returns a json representation of a bibliography repository object.',
            'parameters'    => '{}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // calendar_v1
        $this->docs['get/v1/cal/events/:user_id'] = array(
            'route'         => '/v1/cal/events/:user_id',
            'verb'          => 'GET',
            'group'         => '/v1/cal',
            'description'   => 'Returns the calendar events of a user specified by user_id. Events are only returned for a user if the user is the authorized user or a user owns the administrator role.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/cal/events'] = array(
            'route'         => '/v1/cal/events',
            'verb'          => 'GET',
            'group'         => '/v1/cal',
            'description'   => 'Returns the calendar events of the authenticated user.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/cal/icalurl/:user_id'] = array(
         'route'         => '/v1/cal/icalurl/:user_id',
         'verb'          => 'GET',
         'group'         => '/v1/cal',
         'description'   => 'Returns the ICAL Url of the desktop calendar of a user specified by its user_id.',
         'parameters'    => '{}'
        );

        $this->docs['get/v1/cal/icalurl'] = array(
            'route'         => '/v1/cal/icalurl',
            'verb'          => 'GET',
            'group'         => '/v1/cal',
            'description'   => 'Returns the ICAL Url of the desktop calendar of the authenticated user.',
            'parameters'    => '{}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // contacts_v1
        $this->docs['get/v1/contacts'] = array(
            'route'         => '/v1/contacts',
            'verb'          => 'GET',
            'group'         => '/v1/contacts',
            'description'   => 'Returns the personal ILIAS contacts of the authenticated user.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/contacts/:user_id'] = array(
            'route'         => '/v1/contacts/:user_id',
            'verb'          => 'GET',
            'group'         => '/v1/contacts',
            'description'   => 'Admin: Returns all contacts of a user specified by user_id.',
            'parameters'    => '{}'
        );

        $this->docs['post/v1/contacts/add'] = array(
            'route'         => '/v1/contacts/add',
            'verb'          => 'POST',
            'group'         => '/v1/contacts',
            'description'   => 'Creates a new contact entry to the contact list of the authenticated user.Requires POST variables: login, firstname, lastname, email.',
            'parameters'    => '{"login":"newbie", "firstname":"John", "lastname":"Doe", "email":"john@doe.com"}'
        );

        $this->docs['delete/v1/contacts/:addr_id'] = array(
            'route'         => '/v1/contacts/:addr_id',
            'verb'          => 'DELETE',
            'group'         => '/v1/contacts',
            'description'   => 'Deletes entry specified by addr_id from the contact list of the authenticated user.',
            'parameters'    => '{}'
        );

        $this->docs['put/v1/contacts/:addr_id'] = array(
            'route'         => '/v1/contacts/:addr_id',
            'verb'          => 'PUT',
            'group'         => '/v1/contacts',
            'description'   => 'Updates contact entry addr_id of the authenticated user. Note: it is sufficient to specify only the field that needs to be changed.',
            'parameters'    => '{"login":"newbie", "firstname":"Jonny", "lastname":"Doe", "email":"john@doe.com"}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // courses_v1
        $this->docs['get/v1/courses'] = array(
            'route'         => '/v1/courses',
            'verb'          => 'GET',
            'group'         => '/v1/courses',
            'description'   => 'Retrieves a list of all courses of the authenticated user and meta-information about them (no content).',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/courses/:ref_id'] = array(
            'route'         => '/v1/courses/:ref_id',
            'verb'          => 'GET',
            'group'         => '/v1/courses',
            'description'   => 'Retrieves more detailed information about a course specified by its ref_id. Besides the basic information a list of repository object descriptions are provided and a list of user_ids that belong to the course.',
            'parameters'    => '{}'
        );

        $this->docs['post/v1/courses'] = array(
            'route'         => '/v1/courses',
            'verb'          => 'POST',
            'group'         => '/v1/courses',
            'description'   => 'Creates a new course. Please provide the ref_id of the parent repository object, title and description. Note that the new course will be offline initially.',
            'parameters'    => '{"parent_ref_id":"62", "title":"Test Course2", "description" : "A meaningful description."}'
        );

        $this->docs['delete/v1/courses/:ref_id'] = array(
            'route'         => '/v1/courses/:ref_id',
            'verb'          => 'DELETE',
            'group'         => '/v1/courses',
            'description'   => 'Deletes a course specified by its ref_id.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/courses/join/:ref_id'] = array(
            'route'         => '/v1/courses/join/:ref_id',
            'verb'          => 'GET',
            'group'         => '/v1/courses',
            'description'   => 'Adds the authenticated user as a member to a course specified by the parameter ref_id.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/courses/leave/:ref_id'] = array(
            'route'         => '/v1/courses/leave/:ref_id',
            'verb'          => 'GET',
            'group'         => '/v1/courses',
            'description'   => 'Removes the authenticated user from a course specified by the parameter ref_id.',
            'parameters'    => '{}'
        );

        $this->docs['post/v1/courses/enroll'] = array(
            'route'         => '/v1/courses/enroll',
            'verb'          => 'POST',
            'group'         => '/v1/courses',
            'description'   => 'Admin: Enrolls a user to a course. Expects a "mode" parameter ("by_login"/"by_id") that determines the lookup method for the user. If "mode" is "by_login" then the "login" parameter will be used for the lookup (internal or ldap). If "mode" is "by_id" then the parameter "usr_id" will be used for the lookup. The user will be enrolled in the course specified by crs_ref_id.',
            'parameters'    => '{"mode":"by_id", "usr_id":"240","crs_ref_id":"111"}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // desktop_v1
        $this->docs['get/v1/desktop/overview'] = array(
            'route'         => '/v1/desktop/overview',
            'verb'          => 'GET',
            'group'         => '/v1/desktop',
            'description'   => 'Retrieves all items from the personal desktop of the authenticated user.',
            'parameters'    => '{}'
        );

        $this->docs['post/v1/desktop/overview'] = array(
            'route'         => '/v1/desktop/overview',
            'verb'          => 'POST',
            'group'         => '/v1/desktop',
            'description'   => 'Adds an item specified by ref_id to the users\'s desktop. The user must be the owner or at least has read access of the item.',
            'parameters'    => '{"ref_id":"63"}'
        );

        $this->docs['delete/v1/desktop/overview'] = array(
            'route'         => '/v1/desktop/overview',
            'verb'          => 'DELETE',
            'group'         => '/v1/desktop',
            'description'   => 'Deletes an item specified by ref_id from the personal desktop of the authenticated user.',
            'parameters'    => '{"ref_id":"63"}'
        );

        // /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // docs_v1
        $this->docs['get/v1/docs/route'] = array(
            'route'         => '/v1/docs/route',
            'verb'          => 'GET',
            'group'         => '/v1/docs',
            'description'   => 'Retrieves meta-information about a particular route. The following parameters must be specified: verb and route.',
            'parameters'    => '{"verb":"get","route":"/v1/courses/:ref_id"}'
        );

        $this->docs['get/v1/docs/routes'] = array(
            'route'         => '/v1/docs/routes',
            'verb'          => 'GET',
            'group'         => '/v1/docs',
            'description'   => 'Retrieves meta-information on all documented routes.',
            'parameters'    => '{}'
        );

        $this->docs['get/v1/docs/api'] = array(
            'route'         => '/v1/docs/api',
            'verb'          => 'GET',
            'group'         => '/v1/docs',
            'description'   => 'Provides the API documentation as html page. Needs to be called within a web browser.',
            'parameters'    => '{}'
        );



    }

    /**
     * Creates an internal (single-) key representation.
     * @param $route
     * @param $verb
     * @return string
     */
    private function getInternalKey($route, $verb)
    {
        $combinedKey = '';
        if (strlen($route)>0) {
            $loRoute = strtolower($route);
            $loVerb = strtolower($verb);
            if ($loRoute[0] == '/') {
                $combinedKey = $loVerb.$loRoute;
            } else {
                $combinedKey = $loVerb.'/'.$loRoute;
            }
        }
        return $combinedKey;
    }

    /**
     * Returns the documentation of a particular (route, verb) pair.
     * @param $route
     * @param $verb
     * @return array
     */
    function getDocumentation($route, $verb)
    {
        $result = array();
        $result [] = $this->docs[$this->getInternalKey($route, $verb)];
        return $result;
    }

    /**
     * Returns the documentation of all available (route, verb) pairs
     * @return array
     */
    function getCompleteApiDocumentation()
    {
        $result = array();
        foreach ($this->docs as $key => $value) {
            $result[] = $value;
        }
        return $result;
    }
    
}
