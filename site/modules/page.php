<?php

class Page {
    private $template;

    public function __construct($template) {
        if (!file_exists($template)) {
            die("Șablonul $template nu există!");
        }
        $this->template = $template;
    }

    public function Render($data) {
        $content = file_get_contents($this->template);
        
        if (!$content) {
            die("Nu s-a putut citi șablonul!");
        }
        
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $content = str_replace("{{" . $key . "}}", $value, $content);
            }
        }
        
        return $content;
    }
}