<?php

declare(strict_types=1);

namespace Question;

use Aaron\InquirerPhp\Question\Select;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Aaron\InquirerPhp\Question\Select
 */
final class SelectTest extends TestCase
{
    public const SOME_MAX_LENGTH = 1024;

    /**
     * @var resource
     */
    private $input;

    /**
     * @var resource
     */
    private $output;

    public function setUp(): void
    {
        parent::setUp();

        // Open i/o streams.
        $this->input = fopen('php://memory', 'w');
        $this->output = fopen('php://memory', 'w');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Close i/o streams.
        fclose($this->input);
        fclose($this->output);
    }

    public function testPropertiesAreCorrectlySet()
    {
        $question = new Select('option', 'What\'s your t-shirt size?', ['small', 'medium', 'large']);
        $this->assertEquals('option', $question->getName());
    }

    public function testSelectQuestionDisplaysPromptTextToOutput()
    {
	    $question = new Select('option', 'What\'s your t-shirt size?', ['small', 'medium', 'large']);

	    // Write in the input stream to choose one option and stop execution.
	    fwrite($this->input, '1' . PHP_EOL);
	    rewind($this->input);

        $ignored = $question->prompt($this->input, $this->output);

        // Read contents from output stream.
        rewind($this->output);
        $output = fread($this->output, self::SOME_MAX_LENGTH);

        $this->assertStringContainsString('What\'s your t-shirt size?', $output);
    }

	public function testSelectQuestionDisplaysAllOptionsToOutput()
	{
		$question = new Select('option', 'What\'s your t-shirt size?', ['small', 'medium', 'large']);

		// Write in the input stream to choose one option and stop execution.
		fwrite($this->input, '1' . PHP_EOL);
		rewind($this->input);

		$ignored = $question->prompt($this->input, $this->output);

		// Read contents from output stream.
		rewind($this->output);
		$output = fread($this->output, self::SOME_MAX_LENGTH);

		$this->assertStringContainsString('small', $output);
		$this->assertStringContainsString('medium', $output);
		$this->assertStringContainsString('large', $output);
	}

    /**
     * @dataProvider data_testSelectQuestionReturnsExpectedNumericalValue
     */
    public function testSelectQuestionReturnsExpectedNumericalValue($expected, $input)
    {
	    $question = new Select('option', 'What\'s your t-shirt size?', ['small', 'medium', 'large']);

        // Write some text in the input stream.
        fwrite($this->input, $input . PHP_EOL);
        rewind($this->input);

        $answer = $question->prompt($this->input, $this->output);

        $this->assertEquals($expected, $answer);
    }

    public function data_testSelectQuestionReturnsExpectedNumericalValue()
    {
        return [
            'first option' => [1, '1'],
            'second option' => [2, '2'],
            'third option' => [3, '3'],
        ];
    }

	/**
	 * @dataProvider data_testSelectQuestionIgnoresInvalidValues
	 */
	public function testSelectQuestionIgnoresInvalidValues($expected, $inputs)
	{
		$question = new Select('option', 'What\'s your t-shirt size?', ['small', 'medium', 'large']);

		// Write some text in the input stream.
		foreach ($inputs as $input) {
			fwrite($this->input, $input . PHP_EOL);
		}
		rewind($this->input);

		$answer = $question->prompt($this->input, $this->output);

		$this->assertEquals($expected, $answer);
	}

	public function data_testSelectQuestionIgnoresInvalidValues()
	{
		return [
			'negative number' => [2, ['-1', '2']],
			'higher number' => [2, ['4', '2']],
		];
	}
}
