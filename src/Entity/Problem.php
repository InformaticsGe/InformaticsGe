<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProblemRepository")
 */
class Problem
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
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="smallint")
     */
    private $timeLimit = 1;

    /**
     * @ORM\Column(type="smallint")
     */
    private $memoryLimit = 64;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $inputType = 'stdin';

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $outputType = 'stdout';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sourceTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sourceUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible = true;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $sampleTests = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProblemTest", mappedBy="problem", orphanRemoval=true, cascade={"persist"})
     */
    private $tests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProblemSubmission", mappedBy="problem", orphanRemoval=true)
     */
    private $submissions;

    public function __construct()
    {
        $this->tests = new ArrayCollection();
        $this->submissions = new ArrayCollection();
    }

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getTimeLimit(): ?int
    {
        return $this->timeLimit;
    }

    public function setTimeLimit(int $timeLimit): self
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    public function getMemoryLimit(): ?int
    {
        return $this->memoryLimit;
    }

    public function setMemoryLimit(int $memoryLimit): self
    {
        $this->memoryLimit = $memoryLimit;

        return $this;
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }

    public function setInputType(string $inputType): self
    {
        $this->inputType = $inputType;

        return $this;
    }

    public function getOutputType(): ?string
    {
        return $this->outputType;
    }

    public function setOutputType(string $outputType): self
    {
        $this->outputType = $outputType;

        return $this;
    }

    public function getSourceTitle(): ?string
    {
        return $this->sourceTitle;
    }

    public function setSourceTitle(?string $sourceTitle): self
    {
        $this->sourceTitle = $sourceTitle;

        return $this;
    }

    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl(?string $sourceUrl): self
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function getSampleTests(): ?array
    {
        return $this->sampleTests;
    }

    public function setSampleTests(?array $sampleTests): self
    {
        $this->sampleTests = $sampleTests;

        return $this;
    }

    /**
     * @return Collection|problemTest[]
     */
    public function getTests(): Collection
    {
        return $this->tests;
    }

    public function addTest(problemTest $test): self
    {
        if (!$this->tests->contains($test)) {
            $this->tests[] = $test;
            $test->setProblem($this);
        }

        return $this;
    }

    public function removeTest(problemTest $test): self
    {
        if ($this->tests->contains($test)) {
            $this->tests->removeElement($test);
            // set the owning side to null (unless already changed)
            if ($test->getProblem() === $this) {
                $test->setProblem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProblemSubmission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(ProblemSubmission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setProblem($this);
        }

        return $this;
    }

    public function removeSubmission(ProblemSubmission $submission): self
    {
        if ($this->submissions->contains($submission)) {
            $this->submissions->removeElement($submission);
            // set the owning side to null (unless already changed)
            if ($submission->getProblem() === $this) {
                $submission->setProblem(null);
            }
        }

        return $this;
    }
}
