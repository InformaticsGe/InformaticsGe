<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProblemSubmissionResultRepository")
 */
class ProblemSubmissionResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $verdict;

    /**
     * @ORM\Column(type="float")
     */
    private $time;

    /**
     * @ORM\Column(type="float")
     */
    private $memory;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $error;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $output;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProblemSubmission", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVerdict(): ?string
    {
        return $this->verdict;
    }

    public function setVerdict(string $verdict): self
    {
        $this->verdict = $verdict;

        return $this;
    }

    public function getTime(): ?float
    {
        return $this->time;
    }

    public function setTime(float $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getMemory(): ?float
    {
        return $this->memory;
    }

    public function setMemory(float $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setOutput(?string $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getSubmission(): ?ProblemSubmission
    {
        return $this->submission;
    }

    public function setSubmission(?ProblemSubmission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }
}
