<?php

namespace App\Models;

use Carbon\Carbon;

class Url
{
    private int $id;
    private string $name;
    private Carbon $createdAt;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->createdAt = Carbon::now('GMT+3');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function setCreatedAt(Carbon $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
