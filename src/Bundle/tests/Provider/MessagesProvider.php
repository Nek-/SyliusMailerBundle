<?php

declare(strict_types=1);

namespace Sylius\Bundle\MailerBundle\tests\Provider;

use Sylius\Bundle\MailerBundle\tests\Model\SentMessage;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class MessagesProvider
{
    public function __construct(private string $spoolDirectory)
    {
    }

    /** @return SentMessage[] */
    public function getMessages(): array
    {
        $finder = new Finder();
        $finder->files()->name('*.message')->in($this->spoolDirectory);

        $messages = array_values(iterator_to_array($finder));
        $parsedMessages = [];

        /** @var SplFileInfo $message */
        foreach ($messages as $message) {
            $contents = unserialize($message->getContents());
            $parsedMessages[] = SentMessage::fromSwiftMessage($contents);
        }

        return $parsedMessages;
    }
}
