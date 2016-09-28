<?php
/**
 * Copyright 2016 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Datastore;

use Google\Cloud\Datastore\DatastoreClient;

class ConceptsTest extends \PHPUnit_Framework_TestCase
{
    /* @var $hasCredentials boolean */
    protected static $hasCredentials;

    /* @var $keys array */
    protected static $keys = [];

    public static function setUpBeforeClass()
    {
        $path = getenv('GOOGLE_APPLICATION_CREDENTIALS');
        self::$hasCredentials = $path && file_exists($path) &&
            filesize($path) > 0;
    }

    public function setUp()
    {
        if (!self::$hasCredentials &&
            getenv('DATASTORE_EMULATOR_HOST') === false) {
            $this->markTestSkipped(
                'No application credentials were found, also not using the '
                . 'datastore emulator');
        }
    }

    public function testUpsertEntity()
    {
        $task = upsert_entity();
        $datastore = new DatastoreClient();
        $task = $datastore->lookup($task->key());
        $this->assertEquals($task['category'], 'Personal');
        $this->assertEquals($task['done'], false);
        $this->assertEquals($task['priority'], 4);
        $this->assertEquals($task['description'], 'Learn Cloud Datastore');
        $this->assertEquals($task->key()->pathEnd()['name'], 'sample_task');
        self::$keys[] = $task->key();
    }

    public function testInsertEntity()
    {
        $task = insert_entity();
        $datastore = new DatastoreClient();
        $task = $datastore->lookup($task->key());
        $this->assertEquals($task['category'], 'Personal');
        $this->assertEquals($task['done'], false);
        $this->assertEquals($task['priority'], 4);
        $this->assertEquals($task['description'], 'Learn Cloud Datastore');
        $this->assertArrayHasKey('id', $task->key()->pathEnd());
        self::$keys[] = $task->key();
    }

    public static function tearDownAfterClass()
    {
        $datastore = new DatastoreClient();
        $datastore->deleteBatch(self::$keys);
    }
}
