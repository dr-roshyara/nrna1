<?php
// Golden fixture: two statics linked by an internal behavioural relationship.
// Neither touches $this, but the call connects them -> LCOM4 = 1
class StaticChainExample {
    public static function alpha() { return self::beta(); }
    public static function beta()  { return 42; }
}
