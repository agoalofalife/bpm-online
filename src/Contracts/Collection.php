<?php

namespace agoalofalife\bpm\Contracts;

interface Collection
{
    public function toArray();

    public function toJson();

    public function getData();
}