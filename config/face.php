<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Face Verification Security Engine Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for @vladmandic/human engine, anti-spoofing, liveness,
    | similarity metric, and lockout rules.
    |
    */

    'engine_version' => env('FACE_ENGINE_VERSION', 'human-v3'),

    // Metric similarity: cosine similarity between Human embeddings.
    // Human embeddings are typically unit normalized. Cosine similarity ranges from -1 to 1.
    // Same person typically has similarity >= 0.70 (0.75 - 0.85 typical).
    'match_threshold' => (float) env('FACE_MATCH_THRESHOLD', 0.70),

    // Anti-spoofing score threshold (0.0 = fake/photo/screen, 1.0 = real human)
    'antispoof_threshold' => (float) env('FACE_ANTISPOOF_THRESHOLD', 0.40),

    // Liveness detection threshold (0.0 = static/inanimate, 1.0 = live interactive movement)
    'liveness_threshold' => (float) env('FACE_LIVENESS_THRESHOLD', 0.40),

    // Maximum failed verification attempts before temporary lockout
    'max_attempts' => (int) env('FACE_MAX_ATTEMPTS', 5),

    // Lockout duration in minutes after exceeding max failed attempts
    'lock_minutes' => (int) env('FACE_LOCK_MINUTES', 15),

    // Session valid duration in hours for face authentication state
    'session_hours' => (int) env('FACE_SESSION_HOURS', 8),

    // Challenge nonce TTL in seconds
    'challenge_ttl_seconds' => (int) env('FACE_CHALLENGE_TTL', 60),

    // Debug / Calibration mode (exposes similarity, antispoof, liveness telemetry if APP_DEBUG=true)
    'debug_mode' => (bool) env('FACE_DEBUG_MODE', env('APP_DEBUG', false)),
];
