<?php

namespace Arc\Models;

class UserModel {
           
    static function getUserByEmail($email) {
        $db = \Arc\ArcSystem::getDBConnection();
        $user = $db->get('arc_users',
             ['ID', 'Firstname', 'Lastname', 'EmailAddress', 'PasswordHash', 'LastLogin', 'AccountCreated'],
             ['EmailAddress' => $email]);
        return $user;
    }

    static function updateLastLogin($userid) {
        $db = \Arc\ArcSystem::getDBConnection();
        $db->update('arc_users', ['LastLogin' => date("Y-m-d H:i:s")], ['ID' => $userid]);
    }

    static function updateUserToken($userid) {
        $db = \Arc\ArcSystem::getDBConnection();
        $token = bin2hex(random_bytes(30));

        $exists = $db->count('arc_user_tokens', ['UserID' => $userid]);
        if ($exists <= 0) {
            $db->insert('arc_user_tokens', ['UserID' => $userid,
                'TokenDateTime' => date("Y-m-d H:i:s"), 'TokenData' => $token]);
        } else {
            $db->update('arc_user_tokens', ['TokenData' => $token, 'TokenDateTime' => date("Y-m-d H:i:s")], ['UserID' => $userid]);
        }

        \Arc\ArcSystem::getModel('log');
        \Arc\Models\LogModel::log('User token renewed for UserID: \'' . $userid . '\' - ' . date("Y-m-d H:i:s"), 'INFO', 'TOKEN');
        return $token;
    }

    static function getUserByToken($token) {
        $db = \Arc\ArcSystem::getDBConnection();
        $t = $db->get('arc_user_tokens',
             ['ID', 'UserID', 'TokenDateTime', 'TokenData'],
             ['TokenData' => $token]);

        \Arc\ArcSystem::getModel('log');

        if ($t == null) {
            // doesnt exist
            \Arc\Models\LogModel::log('User token (' . $token . ') invalid, session destroyed.', 'ERROR', 'TOKEN');
            session_destroy();
            return null;
        }

        // get time/date + 1 hour
        $timestamp = strtotime($t['TokenDateTime']);
        if (strtotime("-1 hour") >= $timestamp) {
            // expired + delete
            \Arc\Models\LogModel::log('User token (' . $token . ') expired, session destroyed.', 'WARN', 'TOKEN');
            $db->delete('arc_user_tokens', ['TokenData' => $token]);
            session_destroy();
            return null;
        }

        $db->update('arc_user_tokens', ['TokenDateTime' => date("Y-m-d H:i:s")], ['TokenData' => $token]);
        $user = $db->get('arc_users',
             ['ID', 'Firstname', 'Lastname', 'EmailAddress', 'PasswordHash', 'LastLogin', 'AccountCreated'],
             ['ID' => $t['UserID']]);
        \Arc\Models\LogModel::log('User token (' . $token . ') found user \'' . $user['EmailAddress'] . '\'.', 'INFO', 'TOKEN');
        return $user;
    }

    static function registerUser($firstname, $lastname, $email, $password) {
        $db = \Arc\ArcSystem::getDBConnection();
        $db->insert('arc_users', [
            'Firstname' => $firstname,
            'Lastname' => $lastname,
            'EmailAddress' => $email,
            'PasswordHash' => password_hash($password, PASSWORD_DEFAULT),
            'AccountCreated' => date("Y-m-d H:i:s")
        ]);
        
        \Arc\Models\LogModel::log('User registered (' . $email . ').', 'INFO', 'REGISTER');
        return $db->id();
    }

}