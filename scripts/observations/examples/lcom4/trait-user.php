<?php
// Golden fixture (PINNED LIMITATION): trait methods are NOT resolved;
// only the one declared method counts -> LCOM4 = 1
trait SomeBehavior { public function traitMethod() { return $this->hidden; } }
class TraitUserExample {
    use SomeBehavior;
    private $own;
    public function ownMethod() { return $this->own; }
}
