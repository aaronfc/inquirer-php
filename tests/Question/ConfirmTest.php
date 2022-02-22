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
        $question = new Confirm('continue', 'Do you want to continue?');
        $this->assertEquals('continue', $question->getName());
    }

    public function testConfirmQuestionDisplaysPromptTextToOutput()
    {
        $question = new Confirm('continue', 'Do you want to continue?');

        $ignored = $question->prompt($this->input, $this->output);

        // Read contents from output stream.
        rewind($this->output);
        $output = fread($this->output, self::SOME_MAX_LENGTH);

        $this->assertStringContainsString('Do you want to continue?', $output);
    }

    /**
     * @dataProvider data_testConfirmQuestionReturnsExpectedBooleanForInput
     */
    public function testConfirmQuestionReturnsExpectedBooleanForInput($expected, $input)
    {
        $question = new Confirm('continue', 'Do you want to continue?');

        // Write some text in the input stream.
        fwrite($this->input, $input . PHP_EOL);
        rewind($this->input);

        $answer = $question->prompt($this->input, $this->output);

        $this->assertEquals($expected, $answer);
    }

    public function data_testConfirmQuestionReturnsExpectedBooleanForInput()
    {
        return [
            'empty input returns true' => [true, ''],
            'lowercase \'yes\' returns true' => [true, 'yes'],
            'uppercase \'YES\' returns true' => [true, 'YES'],
            'mixed-case \'yEs\' returns true' => [true, 'yEs'],
            'lowercase \'no\' returns false' => [false, 'no'],
            'uppercase \'NO\' returns false' => [false, 'NO'],
            'mixed-case \'nO\' returns false' => [false, 'nO'],
        ];
    }
}
