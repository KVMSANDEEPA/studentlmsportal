<?php
// Basic app configuration

define('APP_NAME', 'Simple LMS');

// Database settings - adjust for your environment
// Consider using environment variables in production
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'lms');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');

// Base URL (optional). Leave empty for relative links
if (!defined('BASE_URL')) define('BASE_URL', getenv('BASE_URL') ?: '');
