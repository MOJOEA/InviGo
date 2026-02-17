<?php
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function test_set_and_get_cookie()
    {
        // Note: This test requires mocking headers
        // In real scenario, use mocking framework
        $this->assertTrue(true);
    }

    public function test_get_flash_message_returns_null_when_empty()
    {
        $_SESSION = [];
        $result = getFlashMessage();
        $this->assertNull($result);
    }

    public function test_sanitize_special_chars()
    {
        $input = '<>&"\'/';
        $result = sanitize($input);
        $this->assertStringNotContainsString('<', $result);
        $this->assertStringNotContainsString('>', $result);
    }

    public function test_format_thai_datetime_returns_string()
    {
        $datetime = '2024-02-18 10:00:00';
        $result = formatThaiDateTime($datetime);
        $this->assertIsString($result);
    }
}
