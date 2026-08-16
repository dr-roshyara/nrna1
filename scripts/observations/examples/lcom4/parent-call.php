<?php
// Golden fixture (PINNED EXCLUSION): parent:: targets inherited behaviour, which
// is OUTSIDE the analysed class -> no edge -> LCOM4 = 2
class ParentCallExample extends SomeBase {
    private $q;
    public function one() { return parent::inherited(); }
    public function two() { return $this->q; }
}
