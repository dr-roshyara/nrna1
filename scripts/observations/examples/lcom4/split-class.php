<?php
// Golden fixture: two disjoint variable clusters -> LCOM4 = 2
class SplitExample {
    private $a; private $b;
    public function readA() { return $this->a; }
    public function writeA($v) { $this->a = $v; }
    public function readB() { return $this->b; }
    public function writeB($v) { $this->b = $v; }
}
