<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialProblemRepository")
 */
class MaterialProblem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $problem;

    /**
     * @ORM\Column(type="text")
     */
    private $analyse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlTitle(): ?string
    {
        return $this->urlTitle;
    }

    public function setUrlTitle(?string $urlTitle): self
    {
        $this->urlTitle = $urlTitle;

        return $this;
    }

    public function getProblem(): ?string
    {
        return $this->problem;
    }

    public function setProblem(string $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getAnalyse(): ?string
    {
        return $this->analyse;
    }

    public function setAnalyse(string $analyse): self
    {
        $this->analyse = $analyse;

        return $this;
    }
}
