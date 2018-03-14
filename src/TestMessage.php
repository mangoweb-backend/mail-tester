<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\Message;
use Nette\Mail\MimePart;
use Tester\Assert;


class TestMessage
{
	/** @var Message */
	private $message;

	/** @var MimePart[] */
	private $attachments = [];


	public function __construct(Message $message)
	{
		$this->message = $message;
		$this->attachments = $message->getAttachments();
	}


	public function assertSubject(string $subject): self
	{
		Assert::same($subject, $this->message->subject);
		return $this;
	}


	public function assertRecipient(string $email): self
	{
		$recipients = array_keys($this->message->getHeader('To'));
		Assert::count(1, $recipients);
		Assert::same($email, reset($recipients));
		return $this;
	}


	/**
	 * @param string|array $match
	 */
	public function assertBody($match): self
	{
		if (is_array($match)) {
			$match = '%A?%' . implode('%A?%', $match) . '%A?%';
		}
		assert(is_string($match));
		Assert::match($match, $this->message->getBody());

		return $this;
	}


	public function consumeAttachment(?string $fileName = null, ?string $content = null): TestAttachment
	{
		Assert::true(count($this->attachments) > 0, 'No attachment found');
		$attachment = array_shift($this->attachments);

		$testAttachment = new TestAttachment($attachment);

		if ($fileName !== null) {
			$testAttachment->assertFilename($fileName);
		}
		if ($content !== null) {
			$testAttachment->assertContent($content);
		}

		return $testAttachment;
	}


	/**
	 * @internal
	 */
	public function assertAttachmentConsumed(): void
	{
		Assert::count(0, $this->attachments, 'Unconsumed e-mail attachment found');
	}
}
