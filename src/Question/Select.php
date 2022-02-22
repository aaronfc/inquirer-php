<?php

namespace Aaron\InquirerPhp\Question;

class Select implements Question
{
    private $name;
    private $prompt_text;
    private $options;

    public function __construct($name, $prompt_text, $options)
    {
        $this->name = $name;
        $this->prompt_text = $prompt_text;
        $this->options = $options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function prompt($input, $output)
    {
        fwrite($output, $this->prompt_text . PHP_EOL);

        $i = 1;
        foreach ($this->options as $option) {
            fwrite($output, $i++ . ') ' . $option . PHP_EOL);
        }

        do {
            fwrite($output, 'Option? [1-' . count($this->options) . '] ');
            $user_input = intval(trim(fgets($input)));
        } while ($user_input <= 0 || $user_input > count($this->options));

        return $user_input;
    }
}
