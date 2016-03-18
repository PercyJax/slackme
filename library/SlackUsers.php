<?php
// Slack Users API Object

require_once $LIBRARY_DIR.'/array_column.php';

class SlackUsers {
    public static function getList() {
        return callSlackAPI('users.list');
    }
    public static function getInfo($id) {
        $input['user'] = $id;
        return callSlackAPI('users.info', $input);
    }
    public static function getField($id, $field) {
        return self::getInfo($id)->{$field};
    }
    public static function getName($id) {
        return self::getField($id, 'real_name');
    }
    public static function getFirstName($id) {
        return (self::getField($id, 'profile'))['first_name'];
    }
    public static function getLastName($id) {
        return (self::getField($id, 'profile'))['last_name'];
    }
    public static function getPic($id) {
        return (self::getField($id, 'profile'))['image_original'];
    }
    public static function isBot($id) {
        return self::getField($id, 'is_bot');
    }
}