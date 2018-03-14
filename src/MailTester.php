<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\Message;
use Tester\Assert;


class MailTester
{
	/** @var TestMailer */
	private $mailer;

	/** @var TestMessage[] */
	private $consumedMessages = [];


	public function __construct(TestMailer $mailer)
	{
		$this->mailer = $mailer;
	}


	public function assertNone(): void
	{
		Assert::count(0, $this->mailer->messages, 'Unconsumed e-mails sent');
		foreach ($this->consumedMessages as $testMessage) {
			$testMessage->assertAttachmentConsumed();
		}
	}


	/**
	 * @return TestMessage[]
	 */
	public function consumeAll(): array
	{
		$messages = $this->mailer->messages;
		$this->mailer->clear();

		$testMessages = array_map(function (Message $message) {
			return new TestMessage($message);
		}, $messages);
		$this->consumedMessages = array_merge($this->consumedMessages, $testMessages);

		return $testMessages;
	}


	public function consumeSingle(): TestMessage
	{
		Assert::true(count($this->mailer->messages) > 0);
		$message = array_shift($this->mailer->messages);
		return $this->consumedMessages[] = new TestMessage($message);
	}
}
