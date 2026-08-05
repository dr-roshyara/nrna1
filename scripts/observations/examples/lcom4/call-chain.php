<?php
// Golden fixture: a calls b; b and c share $y -> one component -> LCOM4 = 1
class ChainExample {
    private $y;
    public function a() { return $this->b(); }
    public function b() { return $this->y; }
    public function c() { $this->y = 5; }
}
