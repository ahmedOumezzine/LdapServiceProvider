<?php
/**
 * Created by PhpStorm.
 * User: AhmedOumezzine
 * Date: 6/8/2017
 * Time: 8:43 AM
 */
namespace SilexCasts\Provider;
use Silex\ServiceProviderInterface;

class LdapServiceProvider implements ServiceProviderInterface{


    public function register(\Silex\Application $app)
    {
        $app['Ldap'] = function($app) {
            return new \SilexCasts\Provider\LDAP\Client();
        };
    }


    public function boot(\Silex\Application $app)
    {
        // TODO: Implement boot() method.
    }
}