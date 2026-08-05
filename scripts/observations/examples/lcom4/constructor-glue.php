<?php
// Golden fixture: constructor touches both clusters but is EXCLUDED -> LCOM4 = 2
class ConstructorGlueExample {
    private $x; private $y;
    public function __construct() { $this->x = 1; $this->y = 2; }
    public function a() { return $this->x; }
    public function b() { return $this->y; }
}
