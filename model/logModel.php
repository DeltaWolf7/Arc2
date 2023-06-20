<?php

namespace Arc\Models;

class LogModel {
           
    static function log($message, $type = 'INFO', $source = 'ARC') {
        $db = \Arc\ArcSystem::getDBConnection();
        $db->insert('arc_logs', ['Created' => date("Y-m-d H:i:s"), 'Message' => $message, 'Type' => strtoupper($type), 'Source' => strtoupper($source)]);
    }
}