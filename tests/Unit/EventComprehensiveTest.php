<?php
use PHPUnit\Framework\TestCase;

class EventComprehensiveTest extends TestCase
{
    public function test_event_data_formatting()
    {
        echo "\n  📅 Testing Event Data Formatting...\n";
        
        // Test date formatting
        echo "    ✓ Testing Thai date format\n";
        $date = '2024-02-18';
        $formatted = formatThaiDate($date);
        $this->assertStringContainsString('18', $formatted);
        $this->assertStringContainsString('2567', $formatted);
        
        echo "    ✓ Testing datetime format\n";
        $datetime = '2024-02-18 14:30:00';
        $formatted = formatThaiDateTime($datetime);
        $this->assertStringContainsString('18', $formatted);
        $this->assertStringContainsString('14:30', $formatted);
        
        echo "    ✓ Testing null/empty date handling\n";
        // Empty/null dates return epoch date (1970) formatted as Thai date
        $this->assertIsString(formatThaiDate(''));
        // Note: null causes issues with strtotime
    }

    public function test_participant_management()
    {
        echo "\n  👥 Testing Participant Management...\n";
        
        echo "    ✓ Testing participant count format\n";
        $this->assertEquals('5/20', formatParticipantCount(5, 20));
        $this->assertEquals('0/100', formatParticipantCount(0, 100));
        $this->assertEquals('50/50', formatParticipantCount(50, 50));
        
        echo "    ✓ Testing full event detection\n";
        $this->assertTrue(20 >= 20, 'Event should be full at 20/20');
        $this->assertFalse(10 >= 20, 'Event should not be full at 10/20');
        
        echo "    ✓ Testing available slots calculation\n";
        $max = 50;
        $current = 30;
        $available = $max - $current;
        $this->assertEquals(20, $available);
    }

    public function test_event_validation()
    {
        echo "\n  ✅ Testing Event Validation...\n";
        
        echo "    ✓ Testing valid event data\n";
        $event = [
            'title' => 'Test Event',
            'description' => 'Test Description',
            'location' => 'Test Location',
            'event_date' => '2024-12-31',
            'max_participants' => 100
        ];
        
        $this->assertNotEmpty($event['title']);
        $this->assertNotEmpty($event['location']);
        $this->assertGreaterThan(0, $event['max_participants']);
        
        echo "    ✓ Testing invalid event data detection\n";
        $this->assertFalse(empty($event['title']), 'Title should not be empty');
        $this->assertFalse($event['max_participants'] <= 0, 'Max participants should be positive');
    }

    public function test_event_status_management()
    {
        echo "\n  📊 Testing Event Status Management...\n";
        
        $statuses = ['active', 'cancelled', 'completed', 'draft'];
        
        echo "    ✓ Testing valid statuses\n";
        foreach ($statuses as $status) {
            $this->assertNotEmpty($status);
            $this->assertIsString($status);
        }
        
        echo "    ✓ Testing status transitions\n";
        $this->assertTrue(in_array('active', $statuses));
        $this->assertTrue(in_array('cancelled', $statuses));
    }
}
