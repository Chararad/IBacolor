<?php

require_once __DIR__ . '/includes/transparency-ots.php';

echo "Helper file loaded.<br>";

if (function_exists('transparencyCreateOtsProof')) {
    echo "SUCCESS: Function exists.";
} else {
    echo "ERROR: Function not found.";
}