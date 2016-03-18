<?php

require_once $LIBRARY_DIR.'/SlackAttachment.php';

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
        $this->contents["attachments"][] = new SlackAttachment();
        return $this->attachments[count($this->attachments) - 1];
    }

    public function addAttachment($attachment) {
        $this->contents["attachments"][] = $attachment;
        logger(json_encode($attachment));
    }

    public function send() {
        global $SLACKBOT_URL;
        logger("Attachments: " . json_encode($this->contents["attachments"]));
        sendPostJson($SLACKBOT_URL, logger(json_encode($this->contents)));
    //     $curl = curl_init($SLACKBOT_URL);
    //     curl_setopt($curl, CURLOPT_POST, true);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->contents));
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    //     logger(json_decode(logger(curl_exec($curl))));
    //     curl_close($curl);
    }
}
