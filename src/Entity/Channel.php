<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChannelRepository")
 */
class Channel
{
    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     *
     * @Assert\Type("string")
     */
    private $country;

    /**
     * @var Link[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Link", mappedBy="channel", cascade={"persist", "remove"})
     *
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $links;

    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country = null): self
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return Link[]|ArrayCollection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param Link[]|null $links
     *
     * @return self
     */
    public function setLinks(array $links = null): self
    {
        $this->links = new ArrayCollection();
        if ($links) {
            foreach ($links as $link) {
                $this->addLink($link);
            }
        }

        return $this;
    }

    public function addLink(Link $link): self
    {
        $link->setChannel($this);
        $this->links->add($link);

        return $this;
    }

}
