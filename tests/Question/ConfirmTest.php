<?php

declare(strict_types=1);

namespace Question;

use Aaron\InquirerPhp\Question\Confirm;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Aaron\InquirerPhp\Question\Confirm
 */
final class ConfirmTest extends TestCase
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
        $text = new Confirm('continue', 'Do you want to continue?');
        $this->assertEquals('continue', $text->getName());
    }

    public function testConfirmQuestionDisplaysPromptTextToOutput()
    {
        $text = new Confirm('continue', 'Do you want to continue?');

        $ignored = $text->prompt($this->input, $this->output);

        // Read contents from output stream.
        rewind($this->output);
        $output = fread($this->output, self::SOME_MAX_LENGTH);

        $this->assertStringContainsString('Do you want to continue?', $output);
    }

    /**
     * @dataProvider data_testConfirmQuestionReturnsExpectedBooleanForInput
     */
    public function testConfirmQuestionReturnsExpectedBooleanForInput($expected, $input, $case)
    {
        $text = new Confirm('continue', 'Do you want to continue?');

        // Write some text in the input stream.
        fwrite($this->input, $input . PHP_EOL);
        rewind($this->input);

        $answer = $text->prompt($this->input, $this->output);

        $this->assertEquals($expected, $answer, $case);
    }

    public function data_testConfirmQuestionReturnsExpectedBooleanForInput()
    {
        return [
            [true, '', 'Empty input returns true'],
            [true, 'yes', 'Lowercase \'yes\' returns true'],
            [true, 'YES', 'Uppercase \'yes\' returns true'],
            [true, 'yEs', 'Mixed-case \'yes\' returns true'],
            [false, 'no', 'Lowercase \'no\' returns false'],
            [false, 'NO', 'Uppercase \'no\' returns false'],
            [false, 'nO', 'Mixed-case \'no\' returns false'],
        ];
    }
}
