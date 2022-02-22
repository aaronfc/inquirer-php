<?php

namespace Aaron\InquirerPhp\Question;

interface Question
{
    public function getName();
    public function prompt($input, $output);
}
