<?php
/*
 * Copyright 2020 Google LLC.
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

declare(strict_types=1);

namespace Google\Cloud\Samples\CloudSQL\MySQL;

use PDO;

/**
 * Manage votes using the Cloud SQL database.
 */
class Votes
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @param PDO $connection A connection to the database.
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
        $this->createTable();
    }

    /**
     * Creates the table if it does not yet exist.
     *
     * @return void
     */
    private function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS votes (
            vote_id INT NOT NULL AUTO_INCREMENT,
            time_cast DATETIME NOT NULL,
            team VARCHAR(6) NOT NULL,
            PRIMARY KEY (vote_id)
        );";

        $this->connection->exec($sql);
    }

    /**
     * Returns a list of the last five votes
     *
     * @return array
     */
    public function listVotes() : array
    {
        $sql = "SELECT team, time_cast FROM votes ORDER BY time_cast DESC LIMIT 5";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the number of votes cast for a team.
     *
     * @param string $team
     * @param int
     */
    public function getCountByTeam(string $team) : int
    {
        $sql = "SELECT COUNT(vote_id) as voteCount FROM votes WHERE team = ?";

        $statement = $this->connection->prepare($sql);
        $statement->execute([$team]);

        return (int) $statement->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * Insert a new vote into the database
     *
     * @param string $team The team to vote for.
     * @return boolean
     */
    public function insertVote(string $team) : bool
    {
        $sql = "INSERT INTO votes (time_cast, team) VALUES (NOW(), :team)";
        $statement = $this->connection->prepare($sql);
        $statement->bindParam('team', $team);

        return $statement->execute();
    }
}
