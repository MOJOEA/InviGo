<?php
const INCLUDES_DIR = __DIR__;
const ROUTE_DIR = __DIR__ . '/../routes';
const TEMPLATES_DIR = __DIR__ . '/../templates';
const DATABASES_DIR = __DIR__ . '/../databases';
const PUBLIC_ROUTES = ['/', '/login', '/register', '/explore'];
const ALLOW_METHODS = ['GET', 'POST'];
const INDEX_URI = '';
const INDEX_ROUTE = 'explore';

// === Age Validation ===
const MIN_AGE = 10;
const MAX_AGE = 120;


// === OTP Settings ===
const OTP_MODE = 'stateless'; // 'database' หรือ 'stateless'`
const OTP_LENGTH = 6;
const OTP_EXPIRY_MINUTES = 30;
const OTP_EXPIRY_SECONDS = 1800;
const OTP_SECRET_KEY = '0F02iqVR1r3pQ9AAmUdvS3DE48r58';

// === Upload Settings ===
const MAX_UPLOAD_SIZE_MB = 2;
const MAX_UPLOAD_SIZE_BYTES = 2097152; // 2 * 1024 * 1024
const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

// === Password Policy ===
const MIN_PASSWORD_LENGTH = 6;

// === Gender Values ===
const GENDER_MALE = 'male';
const GENDER_FEMALE = 'female';
const GENDER_OTHER = 'other';
const GENDER_LABELS = [
    GENDER_MALE => 'ชาย',
    GENDER_FEMALE => 'หญิง',
    GENDER_OTHER => 'อื่นๆ'
];

// === Registration Status ===
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_REJECTED = 'rejected';

// === Brand Colors ===
const COLOR_PRIMARY = '#FFE600';     // Yellow
const COLOR_SECONDARY = '#D4FF33';   // Lime
const COLOR_ACCENT = '#40E0D0';     // Teal
const COLOR_SUCCESS = '#22c55e';     // Green
const COLOR_WARNING = '#eab308';     // Yellow
const COLOR_DANGER = '#ef4444';      // Red
const COLOR_BG = '#FFFBF0';          // Cream background
