<?php
// Golden fixture: all methods share one instance variable -> LCOM4 = 1
class CohesiveExample {
    private $state;
    public function read() { return $this->state; }
    public function write($v) { $this->state = $v; }
    public function reset() { $this->state = null; }
}
