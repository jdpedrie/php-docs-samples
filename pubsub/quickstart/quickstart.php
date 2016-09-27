<?php

require __DIR__ . '/vendor/autoload.php';

# [START pubsub_quickstart]
# Imports the Google Cloud client library for Google Cloud Pub/Sub
use Google\Cloud\PubSub\PubSubClient;

# Instantiates the client library
$pubsub = new PubSubClient([
    'projectId' => 'YOUR_PROJECT_ID',
]);

# Creates a new topic
$topic = $pubsub->createTopic('my-new-topic');
# [END pubsub_quickstart]
