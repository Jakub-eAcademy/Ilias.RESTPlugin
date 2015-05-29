<?php
/**
 * ILIAS REST Plugin for the ILIAS LMS
 *
 * Authors: D.Schaefer and T. Hufschmidt <(schaefer|hufschmidt)@hrz.uni-marburg.de>
 * 2014-2015
 */
namespace RESTController\extensions\calendar_v1;

// This allows us to use shortcuts instead of full quantifier
use \RESTController\libs as Libs;


/**
 * Route definitions for the REST Calendar API
 */
$app->group('/v1', function () use ($app) {
    /**
     * Returns the calendar events of a user specified by its user_id.
     */
    $app->get('/cal/events/:id', '\RESTController\libs\OAuth2Middleware::TokenRouteAuth', function ($id) use ($app) {
        $auth = new Auth\Util();
        $accessToken = $auth->getAccessToken();
        $user = $accessToken->getUserName();
        $authorizedUserId = $accessToken->getUserId();

        if ($authorizedUserId == $id || Libs\RESTLib::isAdminByUserId($authorizedUserId)) { // only the user or the admin is allowed to access the data
            try {
                $model = new CalendarModel();
                $data = $model->getCalUpcomingEvents($id);

                $app->success($data);
            }
            catch (\Exception $e) {
                $app->halt(500, 'Error: Could not retrieve any events for user '.$id.".", -15);
            }
        }
        else
            $app->halt(401, Libs\RESTLib::MSG_NO_ADMIN, Libs\RESTLib::ID_NO_ADMIN);
    });


    /**
     * Returns the ICAL Url of the desktop calendar of a user specified by its user_id.
     */
    $app->get('/cal/icalurl/:id', '\RESTController\libs\OAuth2Middleware::TokenRouteAuth' , function ($id) use ($app) {
        $auth = new Auth\Util();
        $accessToken = $auth->getAccessToken();
        $user = $accessToken->getUserName();
        $authorizedUserId = $accessToken->getUserId();

        if ($authorizedUserId == $id || Libs\RESTLib::isAdminByUserId($authorizedUserId)) { // only the user or the admin is allowed to access the data
            try {
                $model = new CalendarModel();
                $data = $model->getIcalAdress($id);

                $app->success($data);
            }
            catch (\Exception $e) {
                $app->halt(500, 'Error: Could not retrieve ICAL url for user '.$id.".", -15);
            }
        }
        else
            $app->halt(401, Libs\RESTLib::MSG_NO_ADMIN, Libs\RESTLib::ID_NO_ADMIN);
    });


    /**
     * Returns the calendar events of the authenticated user.
     */
    $app->get('/cal/events', '\RESTController\libs\OAuth2Middleware::TokenRouteAuth', function () use ($app) {
        $auth = new Auth\Util();
        $accessToken = $auth->getAccessToken();
        $user = $accessToken->getUserName();
        $authorizedUserId =  Libs\RESTLib::loginToUserId($user);

        if ($authorizedUserId>-1) { // only the user is allowed to access the data
            $id = $authorizedUserId;
            try {
                $model = new CalendarModel();
                $data = $model->getCalUpcomingEvents($id);

                $app->success($data);
            }
            catch (\Exception $e) {
                $app->halt(500, 'Error: Could not retrieve any events for user '.$id.".", -15);
            }
        }
        else
            $app->halt(401, Libs\RESTLib::MSG_NO_ADMIN, Libs\RESTLib::ID_NO_ADMIN);
    });


    /**
     * Returns the ICAL Url of the desktop calendar of the authenticated user.
     */

    $app->get('/cal/icalurl', '\RESTController\libs\OAuth2Middleware::TokenRouteAuth' , function () use ($app) {
        $auth = new Auth\Util();
        $accessToken = $auth->getAccessToken();
        $user = $accessToken->getUserName();
        $authorizedUserId =  Libs\RESTLib::loginToUserId($user);

        if ($authorizedUserId > -1 ) { // only the user or the admin is allowed to access the data
            $id = $authorizedUserId;
            try {
                $model = new CalendarModel();
                $data = $model->getIcalAdress($id);

                $app->success($data);
            }
            catch (\Exception $e) {
                $app->halt(500, 'Error: Could not retrieve ICAL url for user '.$id.".", -15);
            }
        }
        else
            $app->halt(401, Libs\RESTLib::MSG_NO_ADMIN, Libs\RESTLib::ID_NO_ADMIN);
    });
});
