<?php
require_once __DIR__ . '/../public/index.php';

// Mock functions for testing
function mockGetConnection() {
    return new class {
        public function prepare($sql) {
            return new class {
                public function bind_param(...$args) {}
                public function execute() { return true; }
                public function get_result() {
                    return new class {
                        public $num_rows = 1;
                        public function fetch_assoc() {
                            return [
                                'id' => 1,
                                'name' => 'Test User',
                                'email' => 'test@test.com',
                                'role' => 0
                            ];
                        }
                        public function fetch_all() { return []; }
                    };
                }
            };
        }
        public function query($sql) {
            return $this->prepare($sql)->get_result();
        }
    };
}

// Override getConnection for tests
if (!function_exists('getConnection')) {
    function getConnection() {
        return mockGetConnection();
    }
}
