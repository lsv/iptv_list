<?php

namespace App\Parser;

use App\Entity\Channel;
use App\Entity\Link;
use App\Repository\ChannelRepository;

class InjectParsedLinks
{

    /**
     * @var ChannelRepository
     */
    private $repository;

    /**
     * @var Channel[]
     */
    static private $loadedChannels;

    public function __construct(ChannelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ParsedLinks[] $links
     */
    public function inject(array $links): void
    {
        foreach ($links as $link) {
            if (! $channel = $this->findChannel($link->getChannel(), $link->getCountry())) {
                $channel = (new Channel())
                    ->setName($link->getChannel())
                    ->setCountry($link->getCountry())
                ;

                self::$loadedChannels[$channel->getCountry() . '___' . $channel->getName()] = $channel;
                $this->repository->save($channel, false);
            }

            if (! $this->findLink($channel, $link->getLink())) {
                $newlink = (new Link())
                    ->setUrl($link->getLink())
                ;

                $channel->addLink($newlink);
                $this->repository->save($channel, false);
            }

        }

        $this->repository->save(null, true);

    }

    private function findChannel(string $channelName, ?string $country): ?Channel
    {
        if (! self::$loadedChannels) {
            $this->loadChannels();
        }

        $channel = $country . '___' . $channelName;

        if (! isset(self::$loadedChannels[$channel])) {
            return null;
        }

        return self::$loadedChannels[$channel];

    }

    private function findLink(Channel $channel, string $link): ?Link
    {
        if (! self::$loadedChannels) {
            $this->loadChannels();
        }

        foreach ($channel->getLinks() as $linkObject) {
            if ($linkObject->getUrl() === $link) {
                return $linkObject;
            }
        }

        return null;
    }

    private function loadChannels(): void
    {
        $channels = $this->repository->findAllChannels();
        foreach ($channels as $channel) {
            self::$loadedChannels[$channel->getCountry() . '___' . $channel->getName()] = $channel;
        }
    }

}
