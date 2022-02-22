<?php

declare(strict_types=1);

namespace Question;

use Aaron\InquirerPhp\Question\Text;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Aaron\InquirerPhp\Question\Text
 */
final class TextTest extends TestCase
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
	    $question = new Text('name', 'What is your name?');
        $this->assertEquals('name', $question->getName());
    }

    public function testTextQuestionDisplaysPromptTextToOutput()
    {
	    $question = new Text('name', 'What is your name?');

        $ignored = $question->prompt($this->input, $this->output);

        // Read contents from output stream.
        rewind($this->output);
        $output = fread($this->output, self::SOME_MAX_LENGTH);

        $this->assertStringContainsString('What is your name?', $output);
    }

    public function testTextQuestionReturnsAnswerFromInput()
    {
	    $question = new Text('name', 'What is your name?');

        // Write some text in the input stream.
        fwrite($this->input, 'Aarón Fas' . PHP_EOL);
        rewind($this->input);

        $answer = $question->prompt($this->input, $this->output);

        $this->assertEquals('Aarón Fas', $answer);
    }
}
