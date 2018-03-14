<?php declare(strict_types = 1);

namespace Mangoweb\MailTester;

use Nette\Mail\MimePart;
use Nette\Utils\Strings;
use Tester\Assert;


class TestAttachment
{
	/** @var MimePart */
	private $attachment;


	public function __construct(MimePart $attachment)
	{
		$this->attachment = $attachment;
	}


	public function getAttachment(): MimePart
	{
		return $this->attachment;
	}


	public function assertFilename(string $fileName): self
	{
		Assert::same('attachment; filename="' . Strings::fixEncoding($fileName) . '"', $this->attachment->getHeader('Content-Disposition'));

		return $this;
	}


	public function assertContent(string $content): self
	{
		Assert::same($content, $this->attachment->getBody());

		return $this;
	}
}
