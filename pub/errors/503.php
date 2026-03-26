<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

require_once 'processorFactory.php';

$processorFactory = new \Magento\Framework\Error\ProcessorFactory();
$processor = $processorFactory->createProcessor();
$response = $processor->process503();
$response->sendResponse();


// http_response_code(503);
// header('Retry-After: 3600');
// header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
// header('Pragma: no-cache');

// // Serve static HTML directly
// readfile(__DIR__ . '/503.html');
// exit;
