<?php

namespace WolfDen133\ServerSettings;

class ServerSettings
{
    public const FORM_ID = 3491;

    public const TYPE_PATH = 0;
    public const TYPE_URL = 1;

    private $callable = null;
    private array $data;

    /**
     * @return callable|null
     */
    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function getData () : string
    {
        return json_encode($this->data);
    }

    /**
     * @param callable|null $callable
     */
    public function setCallable(?callable $callable): void
    {
        $this->callable = $callable;
    }

    public function setIcon (int $type, string $data) : void
    {
        $this->data["icon"] = ["type" => ($type == 0 ? "path" : ($type == 1 ? "url" : "")), "data" => $data];
    }

    public function __construct() {
        $this->data["type"] = "custom_form";
        $this->data["title"] = "";
        $this->data["content"] = [];
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : void {
        $this->data["title"] = $title;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @param string $text
     */
    public function addLabel(string $text) : void {
        $this->addContent(["type" => "label", "text" => $text]);
    }

    /**
     * @param string $text
     * @param bool|null $default
     */
    public function addToggle(string $text, bool $default = null) : void {
        $content = ["type" => "toggle", "text" => $text];
        if($default !== null) {
            $content["default"] = $default;
        }
        $this->addContent($content);
    }

    /**
     * @param string $text
     * @param int $min
     * @param int $max
     * @param int $step
     * @param int $default
     */
    public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1) : void {
        $content = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max];
        if($step !== -1) {
            $content["step"] = $step;
        }
        if($default !== -1) {
            $content["default"] = $default;
        }
        $this->addContent($content);
    }

    /**
     * @param string $text
     * @param array $steps
     * @param int $defaultIndex
     */
    public function addStepSlider(string $text, array $steps, int $defaultIndex = -1) : void {
        $content = ["type" => "step_slider", "text" => $text, "steps" => $steps];
        if($defaultIndex !== -1) {
            $content["default"] = $defaultIndex;
        }
        $this->addContent($content);
    }

    /**
     * @param string $text
     * @param array $options
     * @param int|null $default
     */
    public function addDropdown(string $text, array $options, int $default = null) : void {
        $this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
    }

    /**
     * @param string $text
     * @param string $placeholder
     * @param string|null $default
     */
    public function addInput(string $text, string $placeholder = "", string $default = null) : void {
        $this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
    }

    /**
     * @param array $content
     */
    private function addContent(array $content) : void {
        $this->data["content"][] = $content;
    }
}