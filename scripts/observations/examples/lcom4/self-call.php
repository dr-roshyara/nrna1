<?php
// Golden fixture: an instance method uses another behaviour of this class via
// self:: -> internal behavioural relationship -> {one,two} and {three} -> LCOM4 = 2
class SelfCallExample {
    private $p;
    public function one()        { return self::two(); }
    public static function two() { return 42; }
    public function three()      { return $this->p; }
}
