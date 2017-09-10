<?php
namespace SilexCasts\Provider\LDAP;

class Client
{
    public function Hello()
    {
        return "hello";
    }

    public function FindByID($username)
    {
        $Users = array();
        $ldaphost = "ldap://localhost:10389";
        $password = 'secret';  // associated password
        $user = 'uid=admin,ou=system';

        $ldapconn = ldap_connect($ldaphost) or die("Could not connect to LDAP server.");
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0) or die('Unable to set LDAP opt referrals');
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');

        if (!@ldap_bind($ldapconn, $user, $password)) {
            print "<p>Error:" . ldap_error($user) . "</p>";
            print "<p>Error number:" . ldap_errno($user) . "</p>";
            print "<p>Error:" . ldap_err2str(ldap_errno($user)) . "</p>";
        } else {
            //si FILTRE
            $sr = ldap_search($ldapconn, "OU=users,OU=system", "uid=*$username*");
            $info = ldap_get_entries($ldapconn, $sr);
            for ($i = 0; $i < $info["count"]; $i++) {
                $user = array();
                $user["uid"] = isset($info[$i]['uid'][0]) ? $info[$i]['uid'][0] : null;
                $user["givenName"] = isset($info[$i]['givenName'][0]) ? $info[$i]['givenName'][0] : null;
                $user["sn"] = isset($info[$i]['sn'][0]) ? $info[$i]['sn'][0] : null;
                array_push($Users, $user);
            }

            ldap_unbind($ldapconn);
        }
        return $Users;
    }

    public function Authenticate($username, $pass)
    {
        $exist = false;
        $ldaphost = "ldap://localhost:10389";
        $password = 'secret';  // associated password
        $user = 'uid=admin,ou=system';

        $ldapconn = ldap_connect($ldaphost) or die("Could not connect to LDAP server.");
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0) or die('Unable to set LDAP opt referrals');
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');

        if (!@ldap_bind($ldapconn, $user, $password)) {
            print "<p>Error:" . ldap_error($user) . "</p>";
            print "<p>Error number:" . ldap_errno($user) . "</p>";
            print "<p>Error:" . ldap_err2str(ldap_errno($user)) . "</p>";
        } else {
            //si FILTRE
            $sr = ldap_search($ldapconn, "OU=users,OU=system", "CN=$username");
            $info = ldap_get_entries($ldapconn, $sr);
            if ($info["count"] > 0)
                if ($info[0]["userpassword"][0] == $pass)
                    $exist = true;

            ldap_unbind($ldapconn);
        }
        return $exist;
    }
}

?>