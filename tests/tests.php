<?php

require_once __DIR__ . '/testframework.php';

require_once __DIR__ . '/../site/config.php';
require_once __DIR__ . '/../site/modules/database.php';
require_once __DIR__ . '/../site/modules/page.php';

$tests = new TestFramework();

function testDbConnection() {
    global $config;
    
    try {
        $db = new Database($config["db"]["path"]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function testDbCount() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    
    return is_int($count) && $count >= 0;
}

function testDbCreate() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Test Page',
        'content' => '<p>This is a test page content.</p>'
    ];
    
    $id = $db->Create("page", $data);
    
    return is_numeric($id) && $id > 0;
}

function testDbRead() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Test Page',
        'content' => '<p>This is a test page content.</p>'
    ];
    
    $id = $db->Create("page", $data);
    
    $result = $db->Read("page", $id);
    
    return is_array($result) && $result['title'] === 'Page for Read Test';
}

function testDbUpdate() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Test Page',
        'content' => '<p>This is a test page content.</p>'
    ];
    
    $id = $db->Create("page", $data);
    
    $updateData = [
        'title' => 'Updated Title',
        'content' => 'Updated content'
    ];
    
    $db->Update("page", $id, $updateData);
    
    $result = $db->Read("page", $id);
    
    return $result['title'] === 'Updated Title' && $result['content'] === 'Updated content';
}

function testDbDelete() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $data = [
        'title' => 'Test Page',
        'content' => '<p>This is a test page content.</p>'
    ];
    
    $id = $db->Create("page", $data);
    
    $result = $db->Delete("page", $id);
    
    $readResult = $db->Read("page", $id);
    
    return $result === true && empty($readResult);
}

function testDbExecute() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $result = $db->Execute("UPDATE page SET year = '2025' WHERE id = 1");
    
    return $result !== false;
}

function testDbFetch() {
    global $config;
    
    $db = new Database($config["db"]["path"]);
    
    $result = $db->Fetch("SELECT * FROM page LIMIT 1");
    
    return is_array($result) && count($result) > 0;
}

function testPageConstructor() {
    try {
        $page = new Page(__DIR__ . '/../templates/index.tpl');
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function testPageRender() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    
    $data = [
        'title' => 'Test Page',
        'content' => '<p>This is a test page content.</p>'
    ];
    
    $rendered = $page->Render($data);
    
    return strpos($rendered, 'Test Title') !== false && 
           strpos($rendered, 'Test Subtitle') !== false && 
           strpos($rendered, 'Test content') !== false;
}

function testPageRenderInvalidData() {
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    
    $data = "This is not an array";
    
    try {
        $page->Render($data);
        return true; 
    } catch (Exception $e) {
        return false;
    }
}

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

$tests->run();

echo $tests->getResult();
