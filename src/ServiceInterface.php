<?php

namespace Daalvand\CsvGenerator;

use App\Services\CsvGenerator\Exceptions\CsvGeneratorException;

interface ServiceInterface
{
    /**
     * Set if a BOM has to be added to the file
     *
     * @param bool $shouldAddBOM
     * @return $this
     */
    public function setShouldAddBOM(bool $shouldAddBOM): self;

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
    public function setFileName(string $name): self ;

    /**
     * @param string $path
     * @return Service
     */
    public function setFilePath(string $path);

    /**
     * Opens the CSV streamer and makes it ready to accept data.
     * @return $this
     * @throws CsvGeneratorException
     */
    public function openGenerator(): self;

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
    public function close(): self ;

    /**
     * @param Mapper $mapper
     * @return Service
     */
    public function setMapper(Mapper $mapper): self;

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper;
}