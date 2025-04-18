<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../site/config.php';
require_once __DIR__ . '/../site/modules/database.php';
require_once __DIR__ . '/../site/modules/page.php';

$tests = new TestFramework();

// test 1: check database connection
function testDbConnection() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// test 2: test count method
function testDbCount() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    
    return is_int($count) && $count >= 0;
}

// test 3: test create method
function testDbCreate() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Test Page',
        'subtitle' => 'Created for Testing',
        'content' => '<p>This is a test page content.</p>',
        'author' => 'Test Framework',
        'date' => date('d F Y'),
        'year' => date('Y')
    ];
    
    $id = $db->Create("page", $data);
    
    return is_numeric($id) && $id > 0;
}

// test 4: test read method
function testDbRead() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Page for Read Test',
        'subtitle' => 'Testing Read',
        'content' => '<p>Testing the read functionality.</p>',
        'author' => 'Test Framework',
        'date' => date('d F Y'),
        'year' => date('Y')
    ];
    
    $id = $db->Create("page", $data);
    
    $result = $db->Read("page", $id);
    
    return is_array($result) && $result['title'] === 'Page for Read Test';
}

// test 5: test update method
function testDbUpdate() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    // First create a test record
    $data = [
        'title' => 'Original Title',
        'subtitle' => 'Original Subtitle',
        'content' => '<p>Original content.</p>',
        'author' => 'Original Author',
        'date' => date('d F Y'),
        'year' => date('Y')
    ];
    
    $id = $db->Create("page", $data);
    
    $updateData = [
        'title' => 'Updated Title',
        'subtitle' => 'Updated Subtitle'
    ];
    
    $db->Update("page", $id, $updateData);
    
    $result = $db->Read("page", $id);
    
    return $result['title'] === 'Updated Title' && $result['subtitle'] === 'Updated Subtitle';
}

// test 6: test delete method
function testDbDelete() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Page to Delete',
        'subtitle' => 'Testing Delete',
        'content' => '<p>This page should be deleted.</p>',
        'author' => 'Test Framework',
        'date' => date('d F Y'),
        'year' => date('Y')
    ];
    
    $id = $db->Create("page", $data);
    
    $result = $db->Delete("page", $id);
    
    $readResult = $db->Read("page", $id);
    
    return $result === true && empty($readResult);
}

// test 7: test execute method
function testDbExecute() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $result = $db->Execute("UPDATE page SET year = '2025' WHERE id = 1");
    
    return $result !== false;
}

// test 8: test fetch method
function testDbFetch() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $result = $db->Fetch("SELECT * FROM page LIMIT 1");
    
    return is_array($result) && count($result) > 0;
}

// test 9: test page constructor
function testPageConstructor() {
    try {
        $page = new Page(__DIR__ . '/../templates/index.tpl');
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// test 10: test page render method
function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    
    $data = [
        'title' => 'Test Title',
        'subtitle' => 'Test Subtitle',
        'content' => '<p>Test content.</p>',
        'author' => 'Test Author',
        'date' => '18 April 2025',
        'year' => '2025'
    ];
    
    $rendered = $page->Render($data);
    
    return strpos($rendered, 'Test Title') !== false && 
           strpos($rendered, 'Test Subtitle') !== false && 
           strpos($rendered, 'Test content') !== false;
}

// test 11: test page render with invalid template
function testPageRenderInvalidData() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    
    // Test with non-array data
    $data = "This is not an array";
    
    try {
        $page->Render($data);
        return true; // Should not crash
    } catch (Exception $e) {
        return false;
    }
}

// add tests
$tests->add('Database connection', 'testDbConnection');
$tests->add('Database count method', 'testDbCount');
$tests->add('Database create method', 'testDbCreate');
$tests->add('Database read method', 'testDbRead');
$tests->add('Database update method', 'testDbUpdate');
$tests->add('Database delete method', 'testDbDelete');
$tests->add('Database execute method', 'testDbExecute');
$tests->add('Database fetch method', 'testDbFetch');
$tests->add('Page constructor', 'testPageConstructor');
$tests->add('Page render method', 'testPageRender');
$tests->add('Page render with invalid data', 'testPageRenderInvalidData');

// run tests
$tests->run();

echo $tests->getResult();