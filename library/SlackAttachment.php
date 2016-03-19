<?php

// Attachment class for Slack Messages

class SlackAttachment implements JsonSerializable {
    public function jsonSerialize() {
        return (object)$this->contents;
    }

    private $contents = array();

    function __construct() {
        $this->contents["fields"] = array();
        $this->contents['mrkdwn_in'] = array();
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
    public function setMarkdowns($markdowns) {$this->contents["mrkdwn_in"];}
    public function getMarkdowns() {return $this->contents["mrkdwn_in"];}

    public function addField($title = "", $value = "", $short = false) {
        $this->contents["fields"][] = (object)array("title"=>$title, "value"=>$value, "short"=>$short);
    }
    public function addMarkdown($field) {
        $this->contents["mrkdwn_in"][] = $field;
    }

}

