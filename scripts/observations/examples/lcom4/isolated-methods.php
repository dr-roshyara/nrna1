<?php
// Golden fixture: two methods touching nothing shared -> LCOM4 = 2
class IsolatedExample {
    public function a() { return 1; }
    public function b() { return 2; }
}
