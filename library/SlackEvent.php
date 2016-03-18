<?php
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
        return logger($token === $this->token);
    }

    public function isBot($bot_id = null) {
        if (is_null($bot_id)) {
            return $this->event_type === "bot";
        } else {
            return $this->bot_id === $bot_id;
        }
    }

    // Common to all
    private $event_type;
    private $token;
    private $team_id;
    private $team_domain;
    private $channel_id;
    private $channel_name;
    private $user_id;
    private $user_name;
    private $text;

    // Messages
    private $timestamp;
    private $trigger_word;

    // Commands
    private $command;
    private $response_url;

    // Bots
    private $bot_id;
    private $bot_name;

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
            if (isset($_POST["bot_id"])) {
                $this->event_type = "bot";
                $this->bot_id = $_POST["bot_id"];
                $this->bot_name = $_POST["bot_name"];
            }
            $this->timestamp = $_POST["timestamp"];
            $this->trigger_word = $_POST["trigger_word"];
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


    // Common to all
    public function getEventType() {return $this->event_type;}
    public function getToken() {return $this->token;}
    public function getTeamID() {return $this->team_id;}
    public function getTeamDomain() {return $this->team_domain;}
    public function getChannelID() {return $this->channel_id;}
    public function getChannelName() {return $this->channel_name;}
    public function getUserID() {return $this->user_id;}
    public function getUserName() {return $this->user_name;}
    public function getText() {return $this->text;}

    // Messages
    public function getTimeStamp() {return $this->timestamp;}
    public function getTriggerWord() {return $this->trigger_word;}

    // Commands
    public function getCommand() {return $this->command;}
    public function getResponseURL() {return $this->response_url;}

    // Bots
    public function getBotID() {return $this->bot_id;}
    public function getBotName() {return $this->bot_name;}

    public function respond($response_string, $in_channel = false, $attachments = array()) {
        if ($this->event_type == 'command') {
            sendPostJson($this->getResponseURL(), json_encode(array('text' => $response_string, 'response_type' => ($in_channel ? 'in_channel' : 'ephemeral'), 'attachments' => $attachments)));
        } elseif ($this->event_type == 'message') {
            echo json_encode(array('text' => $response_string, 'attachments' => $attachments));
        }
    }
}