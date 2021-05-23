<?php
/**
 * Copyright 2021 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/firestore/README.md
 */

namespace Google\Cloud\Samples\Firestore;

use Google\Cloud\Firestore\FirestoreClient;

/**
 * Query using the Not In operator.
 * ```
 * query_filter_not_in('your-project-id');
 * ```
 */
function query_filter_not_in($projectId)
{
    // Create the Cloud Firestore client
    $db = new FirestoreClient([
        'projectId' => $projectId,
    ]);
    $citiesRef = $db->collection('samples/php/cities');
    # [START firestore_query_filter_not_in]
    $stateQuery = $citiesRef->where(
        'country',
        \Google\Cloud\Firestore\V1\StructuredQuery\FieldFilter\Operator::NOT_IN,
        ["USA", "Japan"]
    );
    # [END firestore_query_filter_not_in]
    foreach ($stateQuery->documents() as $document) {
        printf('Document %s returned by query not_in ["USA","Japan"].' . PHP_EOL, $document->id());
    }
}

require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
