<?php

namespace Noxxie\Database\Progress;
use PDO;

use Illuminate\Database\Connection;
use Noxxie\Database\Progress\Query\Grammer\ProgressGrammer;

/**
 * Class ProgressConnection
 */
class ProgressConnection extends Connection
{
    /**
     * The name of the default schema.
     *
     * @var string
     */
    protected $defaultSchema;
    /**
     * The name of the current schema in use.
     *
     * @var string
     */
    protected $currentSchema;

    public function __construct(PDO $pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
    }

    /**
     * Get the name of the default schema.
     *
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }

    /**
     * Reset to default the current schema.
     *
     * @return string
     */
    public function resetCurrentSchema()
    {
        $this->setCurrentSchema($this->getDefaultSchema());
    }

    /**
     * Set the name of the current schema.
     *
     * @param $schema
     *
     * @return string
     */
    public function setCurrentSchema($schema)
    {
        //$this->currentSchema = $schema;
        $this->statement('SET SCHEMA ?', [strtoupper($schema)]);
    }

    /**
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        $defaultGrammar = new ProgressGrammer;

        // set date format if any specified
        if (array_key_exists('date_format', $this->config)) {
            $defaultGrammar->setDateFormat($this->config['date_format']);
        }

        // Set owner if any specified in config
        if (array_key_exists('owner', $this->config)) {
            $defaultGrammar->setOwner($this->config['owner']);
        }

        // Set config option if minus sign in column names must be converted
        // Default is true
        $defaultGrammar->setColumnConversion(array_key_exists('convert_minus_columns', $this->config) ? $this->config['convert_minus_columns'] : true);

        return $this->withTablePrefix($defaultGrammar);
    }
}