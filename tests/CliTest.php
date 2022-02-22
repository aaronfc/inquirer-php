<?php

declare(strict_types=1);


use Aaron\InquirerPhp\Cli;
use Aaron\InquirerPhp\Question\Question;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Aaron\InquirerPhp\Cli
 */
final class CliTest extends TestCase
{
    public const ANY_ANSWER = 'any-answer';
    /**
     * @var Cli
     */
    private $cli;

    public function setUp(): void
    {
        parent::setUp();
        $this->stdin = STDIN;
        $this->stdout = STDOUT;
        $this->cli = new Cli($this->stdin, $this->stdout);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testEmptyQuestionsDoesNothing()
    {
        $output = $this->cli->prompt([]);
        $this->assertEmpty($output);
    }

    public function testSingleQuestionReturnsAnswerAsIndexedArray()
    {
        $question = $this->mockQuestion('question1', self::ANY_ANSWER);

        $answers = $this->cli->prompt([$question]);

        $this->assertCount(1, $answers);
        $this->assertArrayHasKey('question1', $answers);
        $this->assertEquals(self::ANY_ANSWER, $answers['question1']);
    }

    public function testMultipleQuestionsReturnMultipleAnswersAsIndexedArray()
    {
        $questions = [];
        $questions[] = $this->mockQuestion('question1', 'answer 1');
        $questions[] = $this->mockQuestion('question2', 'answer 2');
        $questions[] = $this->mockQuestion('question3', 'answer 3');

        $answers = $this->cli->prompt($questions);

        $this->assertCount(3, $answers);
        $this->assertArrayHasKey('question1', $answers);
        $this->assertArrayHasKey('question2', $answers);
        $this->assertArrayHasKey('question3', $answers);
        $this->assertEquals('answer 1', $answers['question1']);
        $this->assertEquals('answer 2', $answers['question2']);
        $this->assertEquals('answer 3', $answers['question3']);
    }

    private function mockQuestion($name, $answer)
    {
        $question = $this->createMock(Question::class);
        $question->expects($this->any())
            ->method('getName')
            ->willReturn($name);
        $question->expects($this->any())
            ->method('prompt')
            ->with($this->stdin, $this->stdout)
            ->willReturn($answer);

        return $question;
    }
}
