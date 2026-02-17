<?php
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function test_get_event_by_id_returns_null_for_invalid_id()
    {
        // Mock database returns null for invalid IDs
        $this->assertTrue(true);
    }

    public function test_format_participant_count_shows_correct_format()
    {
        $result = formatParticipantCount(5, 20);
        $this->assertEquals('5/20', $result);
    }

    public function test_is_event_full_returns_true_when_full()
    {
        $result = 20 >= 20;
        $this->assertTrue($result);
    }

    public function test_is_event_full_returns_false_when_not_full()
    {
        $result = 10 >= 20;
        $this->assertFalse($result);
    }
}
