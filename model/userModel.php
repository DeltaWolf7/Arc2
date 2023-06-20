<?php

namespace Arc\Models;

class UserModel {
           
    private static $db = Arc::getDBConnection();

    static function getUserByEmail($emailaddress) {
        $user = $db->get('arc_users',
             ['ID', 'Firstname', 'Lastname', 'EmailAddress', 'PasswordHash', 'LastLogin', 'AccountCreated'],
             ['EmailAddress' => $email]);
        return $user;
    }

    static function updateLastLogin($userid) {
        $db->update('arc_users', ['LastLogin' => date("Y-m-d H:i:s")], ['ID' => $userid]);
    }

    static function updateUserToken($userid) {
        $token = bin2hex(random_bytes(30));

        $exists = $db->count('arc_user_tokens', ['UserID' => $userid]);
        if ($exists <= 0) {
            $db->insert('arc_user_tokens', ['UserID' => $userid,
                'TokenDateTime' => date("Y-m-d H:i:s"), 'TokenData' => $token]);
        } else {
            $db->update('arc_user_tokens', ['TokenData' => $token], [['UserID' => $userid]]);
        }
        return $token;
    }

    static function registerUser($firstname, $lastname, $email, $password) {
        $userid = $db->insert('arc_users', [
            'Firstname' => $firstname,
            'Lastname' => $lastname,
            'EmailAddress' => $email,
            'PasswordHash' => password_hash($password, PASSWORD_DEFAULT),
            'AccountCreated' => date("Y-m-d H:i:s")
        ]);
        return $userid;
    }

}