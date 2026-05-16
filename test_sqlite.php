<?php
$db = new SQLite3('test.db');
$db->exec('CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY)');
echo "SQLite works\n";
