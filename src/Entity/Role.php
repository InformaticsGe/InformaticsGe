<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $machineName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titleKA;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $titleEN;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMachineName(): ?string
    {
        return $this->machineName;
    }

    public function setMachineName(string $machineName): self
    {
        $this->machineName = $machineName;

        return $this;
    }

    public function getTitle(string $language = 'ka'): string
    {
        $title = 'ka' === $language
            ? $this->titleKA : $this->titleEN;

        return $title;
    }

    public function getTitleKA(): ?string
    {
        return $this->titleKA;
    }

    public function setTitleKA(string $titleKA): self
    {
        $this->titleKA = $titleKA;

        return $this;
    }

    public function getTitleEN(): ?string
    {
        return $this->titleEN;
    }

    public function setTitleEN(string $titleEN): self
    {
        $this->titleEN = $titleEN;

        return $this;
    }
}
