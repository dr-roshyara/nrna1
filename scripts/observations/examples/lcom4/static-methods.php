<?php
// Golden fixture (PINNED DECISION): statics are nodes; a static touching no
// $this is an isolated component. instance() + helper() -> LCOM4 = 2
class StaticExample {
    private $data;
    public function instance() { return $this->data; }
    public static function helper() { return 42; }
}
