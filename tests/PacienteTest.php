<?php
use PHPUnit\Framework\TestCase;

class PacienteTest extends TestCase {

    public function testEmailValidation() {
        $this->assertFalse(filter_var("correo_malo", FILTER_VALIDATE_EMAIL));
    }

    public function testValidEmail() {
        $this->assertTrue(
            filter_var("test@mail.com", FILTER_VALIDATE_EMAIL) !== false
        );
    }
}
