<?php

namespace Aaron\InquirerPhp\Question;

class Confirm implements Question {
	private $name;
	private $prompt_text;

	public function __construct($name, $prompt_text) {
		$this->name = $name;
		$this->prompt_text = $prompt_text;
	}

	public function getName() {
		return $this->name;
	}

	public function prompt($input, $output) {
		fwrite($output, $this->prompt_text . ' [*yes*/no] ');
		$clean_input = strtolower(trim(fgets($input)));
		return 'yes' === $clean_input || '' === $clean_input;
	}
}