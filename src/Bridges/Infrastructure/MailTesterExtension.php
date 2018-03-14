<?php declare(strict_types = 1);

namespace Mangoweb\MailTester\Bridges\Infrastructure;

use Mangoweb\MailTester\MailTester;
use Mangoweb\MailTester\TestMailer;
use Mangoweb\Tester\Infrastructure\MangoTesterExtension;
use Nette\DI\CompilerExtension;

class MailTesterExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('mailTester'))
			->setClass(MailTester::class);
		$builder->addDefinition($this->prefix('mailTesterContainerHook'))
			->setClass(MailTesterContainerHook::class)
			->addTag(MangoTesterExtension::TAG_HOOK);
		$builder->addDefinition($this->prefix('mailTesterTestCaseListener'))
			->setClass(MailTesterTestCaseListener::class);
		$builder->addDefinition($this->prefix('mailer'))
			->setClass(TestMailer::class)
			->setDynamic()
			->addTag(MangoTesterExtension::TAG_REQUIRE);
	}
}
