<?php
// Functions Library - slack_functions.php

if ($DEBUG == 1) {
    ini_set("log_errors", 1);
    ini_set("error_log", $LOGFILE);
    error_log( "Hello, errors!" );
    file_put_contents($LOGFILE, date('l, F jS Y - g:i:s A')."\n", FILE_APPEND);
    file_put_contents($LOGFILE, 'POST: '.print_r($_POST, true).'GET: '.print_r($_GET, true), FILE_APPEND);
    file_put_contents($LOGFILE, 'BODY: '.print_r(json_decode(file_get_contents('php://input')), true), FILE_APPEND);
    file_put_contents($LOGFILE, "\n", FILE_APPEND);
}

function logger($text) {
    global $DEBUG, $LOGFILE;
    if ($DEBUG == 1) {
        file_put_contents($LOGFILE, $text."\n", FILE_APPEND);
    }
    return $text;
}

function isFromSlack() {
    return isset($_POST["token"]) && $_POST["token"] == $VERIFICATION_TOKEN;
}

function sendPostJson($url, $post_fields_json_str) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, logger($post_fields_json_str));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    logger(json_decode($result = logger(curl_exec($curl))));
    curl_close($curl);
    return $result;
}

////////////////////////////////////////////////////////////////////////////////

// Incoming Slack Commands
//
// token=gIkuvaNzQIHg97ATvDxqgjtO
// team_id=T0001
// team_domain=example
// channel_id=C2147483705
// channel_name=test
// user_id=U2147483697
// user_name=Steve
// command=/weather
// text=94070
// response_url=https://hooks.slack.com/commands/1234/5678

// Incoming Slack Messages
//
// token=XXXXXXXXXXXXXXXXXX
// team_id=T0001
// team_domain=example
// channel_id=C2147483705
// channel_name=test
// timestamp=1355517523.000005
// user_id=U2147483697
// user_name=Steve
// text=googlebot: What is the air-speed velocity of an unladen swallow?
// trigger_word=googlebot:

////////////////////////////////////////////////////////////////////////////////

class SlackEvent {
    // public function isValid() {
    //     global $VERIFICATION_TOKEN;
    //     return $VERIFICATION_TOKEN === $this->token;
    // }
    public function isValid($token = null) {
        if (is_null($token)) {
            $token = $GLOBALS['VERIFICATION_TOKEN'];
        }
        return $token === $this->token;
    }

    private $event_type;
    private $token;
    private $team_id;
    private $team_domain;
    private $channel_id;
    private $channel_name;
    private $timestamp;
    private $user_id;
    private $user_name;
    private $command;
    private $text;
    private $trigger_word;
    private $response_url;

    // Auto-detect
    public function __construct() {
        if (!isset($_POST)) {
            return false;
        }
        if (isset($_POST["command"])) {
            $this->event_type = "command";
            $this->command = $_POST["command"];
            $this->response_url = $_POST["response_url"];
        } else if (isset($_POST["timestamp"])) {
            $this->event_type = "message";
            $this->timestamp = $timestamp;
            $this->trigger_word = $trigger_word;
        } else {
            return null;
        }
        $this->token = $_POST["token"];
        $this->team_id = $_POST["team_id"];
        $this->team_domain = $_POST["team_domain"];
        $this->channel_id = $_POST["channel_id"];
        $this->channel_name = $_POST["channel_name"];
        $this->user_id = $_POST["user_id"];
        $this->user_name = $_POST["user_name"];
        $this->text = $_POST["text"];
        
    }

    // // Slash Command
    // public function __construct($token, $team_id, $team_domain, $channel_id, $channel_name, $user_id, $user_name, $command, $text, $response_url) {
    //     $this->token = $token;
    //     $this->team_id = $team_id;
    //     $this->team_domain = $team_domain;
    //     $this->channel_id = $channel_id;
    //     $this->channel_name = $channel_name;
    //     $this->user_id = $user_id;
    //     $this->user_name = $user_name;
    //     $this->command = $command;
    //     $this->text = $text;
    //     $this->response_url = $response_url;
    //     $this->event_type = "command";
    // }

    // // Message
    // public function __construct($token, $team_id, $team_domain, $channel_id, $channel_name, $timestamp, $user_id, $user_name, $text, $trigger_word) {
    //     $this->token = $token;
    //     $this->team_id = $team_id;
    //     $this->team_domain = $team_domain;
    //     $this->channel_id = $channel_id;
    //     $this->channel_name = $channel_name;
    //     $this->timestamp = $timestamp;
    //     $this->user_id = $user_id;
    //     $this->user_name = $user_name;
    //     $this->text = $text;
    //     $this->trigger_word = $trigger_word;
    //     $this->event_type = "message";
    // }



    public function getEventType() {return $this->event_type;}
    public function getToken() {return $this->token;}
    public function getTeamID() {return $this->team_id;}
    public function getTeamDomain() {return $this->team_domain;}
    public function getChannelID() {return $this->channel_id;}
    public function getChannelName() {return $this->channel_name;}
    public function getTimeStamp() {return $this->timestamp;}
    public function getUserID() {return $this->user_id;}
    public function getUserName() {return $this->user_name;}
    public function getCommand() {return $this->command;}
    public function getText() {return $this->text;}
    public function getTriggerWord() {return $this->trigger_word;}
    public function getResponseURL() {return $this->response_url;}

    public function respond($response_string, $in_channel = false, $attachments = array()) {
        if ($this->event_type == 'command') {
            echo json_encode(array('text' => $response_string, 'response_type' => ($in_channel ? 'in_channel' : 'ephemeral'), 'attachments' => $attachments));
        } elseif ($this->event_type == 'message') {
            echo json_encode(array('text' => $response_string, 'attachments' => $attachments));
        }
    }
}

////////////////////////////////////////////////////////////////////////////////

// Sample outgoing message

// {
//     "text":"Message Text",
//     "username":"Test User",
//     "icon_url":"http://i.groupme.com/512x512.png.7a07bbb9e1a441cfa0cd5a8e05c8b8d4",
//     "icon_emoji":"",
//     "channel":"#general",
//     "attachments": [
//         {
//             "fallback": "Required plain-text summary of the attachment.",
//             "color": "#36a64f",
//             "pretext": "Optional text that appears above the attachment block",
//             "author_name": "Bobby Tables",
//             "author_link": "http://flickr.com/bobby/",
//             "author_icon": "http://flickr.com/icons/bobby.jpg",
//             "title": "Slack API Documentation",
//             "title_link": "https://api.slack.com/",
//             "text": "Optional text that appears within the attachment",
//             "fields": [
//                 {
//                     "title": "Priority",
//                     "value": "High",
//                     "short": true
//                 },
//         {
//             "title": "Location",
//             "value": "USA",
//             "short": true
//         }
//             ],
//             "image_url": "http://my-website.com/path/to/image.jpg",
//             "thumb_url": "http://example.com/path/to/thumb.png"
//         }
//     ]
// }

////////////////////////////////////////////////////////////////////////////////

class SlackMessage implements JsonSerializable {
    
    private $contents = array();

    public function jsonSerialize() {
        return $this->contents;
    }

    // function __construct() {
    //     $this->contents["username"] = "SlackBot";
    //     $this->contents["icon_url"] = "https://blog.agilebits.com/wp-content/uploads/2014/09/Slack-icon.png"
    //     $this->contents["attachments"] = array();
    // }

    // function __construct($message) {
    //     $this->contents["username"] = "SlackBot";
    //     $this->contents["icon_url"] = "https://blog.agilebits.com/wp-content/uploads/2014/09/Slack-icon.png"
    //     $this->contents["attachments"] = array();
    //     $this->contents["text"] = $message;
    // }

    // function __construct($message, $username) {
    //     $this->contents["username"] = $username;
    //     $this->contents["icon_url"] = "https://blog.agilebits.com/wp-content/uploads/2014/09/Slack-icon.png"
    //     $this->contents["attachments"] = array();
    //     $this->contents["text"] = $message;
    // }

    function __construct($message = "", $channel = '#test', $username = 'SlackBot', $icon_url = "https://blog.agilebits.com/wp-content/uploads/2014/09/Slack-icon.png") {
        $this->contents["username"] = $username;
        $this->contents["channel"] = $channel;
        $this->contents["icon_url"] = $icon_url;
        $this->contents["attachments"] = array();
        $this->contents["text"] = $message;
    }

    public function setText($text) {$this->contents["text"] = $text;}
    public function getText() {return $this->text;}
    public function setUserName($username) {$this->contents["username"] = $username;}
    public function getUserName() {return $this->username;}
    public function setIconURL($icon_url) {$this->contents["icon_url"] = $icon_url;}
    public function getIconURL() {return $this->icon_url;}
    public function setIconEmoji($icon_emoji) {$this->contents["icon_emoji"] = $icon_emoji;}
    public function getIconEmoji() {return $this->icon_emoji;}
    public function setChannel($channel) {$this->contents["channel"] = $channel;}
    public function getChannel() {return $this->channel;}
    public function setAttachments($attachments) {$this->contents["attachments"] = $attachments;}
    public function getAttachments() {return $this->attachments;}

    public function &newAttachment() {
        $this->attachments[] = new SlackAttachment();
        return $this->attachments[count($this->attachments) - 1];
    }

    public function addAttachment($attachment) {
        $this->attachments[] = $attachment;
    }

    public function send() {
        global $SLACKBOT_URL;
        sendPostJson($SLACKBOT_URL, logger(json_encode($this->contents)));
    //     $curl = curl_init($SLACKBOT_URL);
    //     curl_setopt($curl, CURLOPT_POST, true);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->contents));
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //     logger(json_decode(logger(curl_exec($curl))));
    //     curl_close($curl);
    }
}

// Attachment class for Slack Messages

class SlackAttachment implements JsonSerializable {
    public function jsonSerialize() {
        return $this->contents;
    }

    private $contents = array();

    function __construct() {
        $this->contents["fields"] = array();
    }
    
    public function setColor($hex_color_str) {$this->contents["color"] = $hex_color_str;}
    public function getColor() {return $this->contents["color"];}
    public function setFallBack($fallback) {$this->contents["fallback"] = $fallback;}
    public function getFallBack() {return $this->contents["fallback"];}
    public function setPreText($pretext) {$this->contents["pretext"] = $pretext;}
    public function getPreText() {return $this->contents["pretext"];}
    public function setAuthorName($author_name) {$this->contents["author_name"] = $author_name;}
    public function getAuthorName() {return $this->contents["author_name"];}
    public function setAuthorLink($author_link) {$this->contents["author_link"] = $author_link;}
    public function getAuthorLink() {return $this->contents["author_link"];}
    public function setAuthorIcon($author_icon) {$this->contents["author_icon"] = $author_icon;}
    public function getAuthorIcon() {return $this->contents["author_icon"];}
    public function setTitle($title) {$this->contents["title"] = $title;}
    public function getTitle() {return $this->contents["title"];}
    public function setTitleLink($title_link) {$this->contents["title_link"] = $title_link;}
    public function getTitleLink() {return $this->contents["title_link"];}
    public function setText($text) {$this->contents["text"] = $text;}
    public function getText() {return $this->contents["text"];}
    public function setImageURL($image_url) {$this->contents["image_url"] = $image_url;}
    public function getImageURL() {return $this->contents["image_url"];}
    public function setThumbURL($thumb_url) {$this->contents["thumb_url"] = $thumb_url;}
    public function getThumbURL() {return $this->contents["thumb_url"];}
    public function setFields($fields) {$this->contents["fields"] = $fields;}
    public function getFields() {return $this->contents["fields"];}

    public function addField($title = "", $value = "", $short = false) {
        $this->contents["fields"][] = (object)array("title"=>$title, "value"=>$value, "short"=>$short);
    }
}

