<?php
declare(strict_types=1);

namespace ReviewParser\Strategy;

class StepByStepIpRounder extends AbstractIpRounder
{
    public function nextElementByError(string $error): void
    {
        $this->IPIterator->removeCurrent();
    }
}
