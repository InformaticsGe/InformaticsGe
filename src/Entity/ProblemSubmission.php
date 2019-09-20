<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProblemSubmissionRepository")
 */
class ProblemSubmission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="problemSubmissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Problem", inversedBy="submissions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $problem;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $verdict;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ProblemSubmissionResult",
     *     mappedBy="submission", orphanRemoval=true, cascade={"persist"}
     * )
     */
    private $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProblem(): ?Problem
    {
        return $this->problem;
    }

    public function setProblem(?Problem $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|ProblemSubmissionResult[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(ProblemSubmissionResult $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setSubmission($this);
        }

        return $this;
    }

    public function removeResult(ProblemSubmissionResult $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getSubmission() === $this) {
                $result->setSubmission(null);
            }
        }

        return $this;
    }

    public function getAcceptedTestsCount()
    {
        $accepted = array_filter($this->results->getValues(), function ($value, $key) {
            /** @var ProblemSubmissionResult $value */
            return 'AC' === $value->getVerdict();
        }, ARRAY_FILTER_USE_BOTH);

        return count($accepted);
    }
}
