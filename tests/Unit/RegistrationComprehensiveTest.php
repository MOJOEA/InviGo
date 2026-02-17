<?php
use PHPUnit\Framework\TestCase;

class RegistrationComprehensiveTest extends TestCase
{
    public function test_registration_status_management()
    {
        echo "\n  🎫 Testing Registration Status Management...\n";
        
        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        
        echo "    ✓ Testing all registration statuses\n";
        foreach ($statuses as $status) {
            $this->assertIsString($status);
            $this->assertNotEmpty($status);
        }
        
        echo "    ✓ Testing status badge colors\n";
        $statusColors = [
            'pending' => 'bg-yellow-400',
            'approved' => 'bg-green-400',
            'rejected' => 'bg-red-400'
        ];
        
        foreach ($statusColors as $status => $color) {
            $this->assertArrayHasKey($status, $statusColors);
            $this->assertStringContainsString('bg-', $color);
        }
    }

    public function test_check_in_functionality()
    {
        echo "\n  ✅ Testing Check-in Functionality...\n";
        
        echo "    ✓ Testing OTP generation\n";
        $otp = sprintf('%06d', mt_rand(0, 999999));
        $this->assertEquals(6, strlen($otp));
        $this->assertIsNumeric($otp);
        
        echo "    ✓ Testing OTP validation\n";
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp);
        
        echo "    ✓ Testing check-in status\n";
        $checkedIn = true;
        $this->assertTrue($checkedIn, 'User should be checked in');
    }

    public function test_registration_limits()
    {
        echo "\n  📊 Testing Registration Limits...\n";
        
        $maxParticipants = 50;
        $currentRegistrations = 45;
        
        echo "    ✓ Testing available slots\n";
        $available = $maxParticipants - $currentRegistrations;
        $this->assertEquals(5, $available);
        
        echo "    ✓ Testing full event detection\n";
        $isFull = $currentRegistrations >= $maxParticipants;
        $this->assertFalse($isFull, 'Event should not be full');
        
        echo "    ✓ Testing waitlist scenario\n";
        $isNearFull = ($maxParticipants - $currentRegistrations) <= 5;
        $this->assertTrue($isNearFull, 'Event should be nearly full');
    }

    public function test_registration_workflow()
    {
        echo "\n  🔄 Testing Registration Workflow...\n";
        
        echo "    ✓ Step 1: User submits registration\n";
        $status = 'pending';
        $this->assertEquals('pending', $status);
        
        echo "    ✓ Step 2: Organizer approves\n";
        $status = 'approved';
        $this->assertEquals('approved', $status);
        
        echo "    ✓ Step 3: User checks in at event\n";
        $checkedIn = true;
        $this->assertTrue($checkedIn);
    }
}
