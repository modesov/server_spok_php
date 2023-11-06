<?php

namespace App\Core;

class Response
{
    protected string | array $result;

    public function setResult($result): static
    {
        $this->result = $result;
        return $this;
    }

    public function send(): void
    {
        echo is_array($this->result) ? $this->prepareJson() : $this->prepareHtml();
    }

    protected function prepareJson(): string
    {
        return json_encode(['data' => $this->result]);
    }

    protected function prepareHtml(): string
    {
        return $this->result;
    }
}