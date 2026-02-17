<?php
// Define test constants
define('BASE_DIR', dirname(__DIR__));
define('TEMPLATES_DIR', BASE_DIR . '/templates');
define('INCLUDES_DIR', BASE_DIR . '/includes');
define('ROUTES_DIR', BASE_DIR . '/routes');
define('DATABASES_DIR', BASE_DIR . '/databases');
define('PUBLIC_DIR', BASE_DIR . '/public');

// Mock getConnection before loading includes
function getConnection() {
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

// Load helpers
require_once INCLUDES_DIR . '/helpers/auth.php';
require_once INCLUDES_DIR . '/helpers/date.php';
require_once INCLUDES_DIR . '/helpers/flash.php';
require_once INCLUDES_DIR . '/helpers/format.php';
require_once INCLUDES_DIR . '/helpers/password.php';
require_once INCLUDES_DIR . '/helpers/sanitize.php';

// Start session for tests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

