<?php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_is_logged_in_returns_false_when_no_session()
    {
        $_SESSION = [];
        $this->assertFalse(isLoggedIn());
    }

    public function test_is_logged_in_returns_true_when_session_exists()
    {
        $_SESSION['user_id'] = 1;
        $this->assertTrue(isLoggedIn());
    }

    public function test_is_admin_returns_false_for_regular_user()
    {
        $_SESSION['user_id'] = 1;
        $this->assertFalse(isAdmin());
    }

    public function test_sanitize_removes_html_tags()
    {
        $input = '<script>alert("xss")</script>Hello';
        $result = sanitize($input);
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringContainsString('Hello', $result);
    }

    public function test_format_thai_date_returns_correct_format()
    {
        $date = '2024-02-18';
        $result = formatThaiDate($date);
        $this->assertStringContainsString('2567', $result);
    }
}
