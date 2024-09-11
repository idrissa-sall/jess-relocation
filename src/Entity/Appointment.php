<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_apm = null;

    #[ORM\Column(length: 2)]
    private ?string $hour = null;

    #[ORM\Column(length: 15)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $submition_date = null;

    #[ORM\Column]
    private ?bool $is_done = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateApm(): ?\DateTimeImmutable
    {
        return $this->date_apm;
    }

    public function setDateApm(\DateTimeImmutable $date_apm): static
    {
        $this->date_apm = $date_apm;

        return $this;
    }

    public function getHour(): ?string
    {
        return $this->hour;
    }

    public function setHour(string $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getSubmitionDate(): ?\DateTimeImmutable
    {
        return $this->submition_date;
    }

    public function setSubmitionDate(\DateTimeImmutable $submition_date): static
    {
        $this->submition_date = $submition_date;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->is_done;
    }

    public function setIsDone(bool $is_done): static
    {
        $this->is_done = $is_done;

        return $this;
    }
}
