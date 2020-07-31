<?php

namespace Daalvand\CsvGenerator;

use Daalvand\CsvGenerator\Exceptions\CsvGeneratorException;

interface GeneratorInterface
{
    /**
     * Set if a BOM has to be added to the file
     *
     * @param bool $shouldAddBOM
     * @return $this
     */
    public function setShouldAddBOM(bool $shouldAddBOM): GeneratorInterface;

    /**
     * Sets the field enclosure for the CSV
     *
     * @param string $enclosure Character that enclose fields (one character only)
     * @return $this
     */
    public function setEnclosure(string $enclosure);

    /**
     * Sets the field delimiter for the CSV
     *
     * @param string $delimiter Character that delimits fields (one character only)
     * @return $this
     */
    public function setDelimiter($delimiter);

    /**
     * @param string $name
     * @return $this
     */
    public function setFileName(string $name): GeneratorInterface;

    /**
     * @param string $path
     * @return $this
     */
    public function setFilePath(string $path): GeneratorInterface;

    /**
     * @param bool $append
     * @return $this
     */
    public function shouldAppend(bool $append): GeneratorInterface;

    /**
     * Opens the CSV streamer and makes it ready to accept data.
     * @return $this
     * @throws CsvGeneratorException
     */
    public function openGenerator(): GeneratorInterface;

    /**
     * assoc array contains field names -> ['id'=> 1, 'name' => 'ali', 'job' => ['id' => 1, 'name' => 'developer']]
     * @param array $row
     * @return $this
     */
    public function addRow(array $row);

    /**
     * @return $this
     * @throws CsvGeneratorException
     */
    public function close(): GeneratorInterface;

    /**
     * set mapper object
     * @param Mapper $mapper
     * @return $this
     */
    public function setMapper(Mapper $mapper): GeneratorInterface;

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper;
}