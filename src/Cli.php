<?php

namespace Aaron\InquirerPhp;

class Cli {

	/**
	 * @var resource
	 */
	private $input;
	/**
	 * @var resource
	 */
	private $output;

	public function __construct($input = STDIN, $output = STDOUT) {
		$this->input = $input;
		$this->output = $output;
	}

	/**
	 * @param Question\Question[] $questions
	 * @return array
	 */
	public function prompt($questions) {
		$answers = [];
		foreach ($questions as $question) {
			$answers[$question->getName()] = $question->prompt($this->input, $this->output);
		}
		return $answers;
	}
}
